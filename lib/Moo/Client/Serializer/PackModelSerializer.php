<?php
namespace Moo\Client\Serializer;

use Moo\PackModel\Pack;
use Moo\PackModel\PhysicalSpec;
use Moo\PackModel\Side;
use Moo\PackModel\Card;
use Moo\PackModel\Extra;
use Moo\PackModel\ImageBasket\ImageBasket;
use Moo\PackModel\ImageBasket\ImageBasketItem;
use Moo\PackModel\ImageBasket\ImageBasketItemImage;

class PackModelSerializer
{
    protected $dataSerializer;

    public function __construct(DataSerializer $dataSerializer)
    {
        $this->dataSerializer = $dataSerializer;
    }

    public function serializePack(Pack $pack)
    {
        return json_encode($this->normalizePack($pack));
    }

    public function normalizePack(Pack $pack)
    {
        $sides  = array();
        $extras = array();
        $cards = array();

        foreach ($pack->getSides() as $side) {
            $sides[] = $this->normalizeSide($side);
        }

        foreach ($pack->getExtras() as $extra) {
            $extras[] = $this->normalizeExtra($extra);
        }

        foreach($pack->getCards() as $card) {
            $cards[] = $this->normalizeCard($card);
        }

        return array(
             'productCode' => $pack->getProductCode(),
             'numCards'    => $pack->getNumCards(),
             'sides'       => $sides,
             'extras'      => $extras,
             'imageBasket' => $this->normalizeImageBasket($pack->getImageBasket()),
        );
    }

    public function denormalizePack($id, $packData)
    {
        $pack = new Pack($id);
        $pack->setProductCode($packData['productCode']);
        $pack->setNumCards($packData['numCards']);
        foreach ($packData['sides'] as $sideData) {
            $pack->addSide($this->denormalizeSide($sideData));
        }
        foreach ($packData['cards'] as $cardData) {
            $pack->addCard($this->denormalizeCard($cardData, $pack->getSides()));
        }
        foreach ($packData['extras'] as $extraData) {
            $pack->addExtra($this->denormalizeExtra($extraData));
        }
        foreach ($packData['imageBasket']['items'] as $item) {
            $pack->getImageBasket()->addItem($this->denormalizeImageBasketItem($item));
        }
        return $pack;
    }

    public function serializeSide(Side $side)
    {
        return json_encode($this->normalizeSide($side));
    }

    public function normalizeSide(Side $side)
    {
        $datae = array();
        foreach ($side->getData() as $data) {
            $datae[] = $this->dataSerializer->serializeData($data);
        }

        return array(
            'type'         => $side->getType(),
            'sideNum'      => $side->getSideNum(),
            'templateCode' => $side->getTemplateCode(),
            'data'         => $datae,
        );
    }

    public function denormalizeSide($sideData)
    {
        $side = new Side(
            $sideData['type'],
            $sideData['sideNum'],
            $sideData['templateCode']
        );
        foreach ($sideData['data'] as $data) {
            $side->addData($this->dataSerializer->deserializeData($data));
        }
        return $side;
    }

    public function normalizeCard(Card $card)
    {
        $sides = array();
        if ($card->getImageSide()) {
            $sides[] = array(
                $card->getImageSide()->getType(),
                $card->getImageSide()->getSideNum()
            );
        }

        if ($card->getDetailsSide()) {
            $sides[] = array(
                $card->getDetailsSide()->getType(),
                $card->getDetailsSide()->getSideNum()
            );
        }

        return array(
            'cardId'    => $card->getId(),
            'cardSides' => $sides,
        );
    }

    public function denormalizeCard($cardData, $packSides)
    {
        $sides = array(
            Side::TYPE_IMAGE   => null,
            Side::TYPE_DETAILS => null,
        );
        foreach ($cardData['cardSides'] as $ref) {
            $sides[$ref['sideType']] = $packSides[$ref['sideType'].'-'.$ref['sideNum']];
        }

        return new Card(
            $cardData['id'],
            $sides[Side::TYPE_IMAGE],
            $sides[Side::TYPE_DETAILS]
        );
    }

    public function normalizeExtra(Extra $extra)
    {
        return array(
            'key'   => $extra->getKey(),
            'value' => $extra->getValue(),
        );
    }

    public function denormalizeExtra($extraData)
    {
        return new Extra($extraData['key'], $extraData['value']);
    }

    public function normalizeImageBasket(ImageBasket $imageBasket)
    {
        $items = array();
        foreach ($imageBasket->getItems() as $item) {
            $items[] = $this->normalizeImageBasketItem($item);
        }

        return json_encode(
            array(
                 'items' => $items,
            )
        );
    }

    public function denormalizeImageBasket($data)
    {
        $basket =  new ImageBasket();
        foreach ($data['items'] as $itemData) {
            $basket->addItem($this->denormalizeImageBasketItem($itemData));
        }
        return $basket;
    }

    public function normalizeImageBasketItem(ImageBasketItem $item)
    {
        $imageItems = array();
        foreach ($item->getImageItems() as $imageItem){
            $imageItems[] = $this->normalizeImageBasketItem($imageItem);
        }

        return array(
             'resourceUri' => $item->getResourceUri(),
             'name'        => $item->getName(),
             'source'      => $item->getSource(),
             'cacheId'     => $item->getCacheId(),
             'imageItems'  => $imageItems,
        );
    }

    public function denormalizeImageBasketItem($data)
    {
        $item = new ImageBasketItem(
            $data['resourceUri'],
            $data['name'],
            $data['source'],
            $data['cacheId']
        );
        foreach ($data['imageItems'] as $imageItem) {
            $item->addImageItem($this->denormalizeImageBasketItemImage($imageItem));
        }
        return $item;
    }

    public function normalizeImageBasketItemImage(ImageBasketItemImage $image)
    {
        return array(
             'type'        => $image->getType(),
             'resourceUri' => $image->getResourceUri(),
             'width'       => $image->getWidth(),
             'height'      => $image->getHeight(),
             'rotation'    => $image->getRotation(),
        );
    }

    public function denormalizeImageBasketItemImage($data)
    {
        return new ImageBasketItemImage(
            $data['type'],
            $data['resourceUri'],
            $data['width'],
            $data['height'],
            $data['rotation']
        );
    }

    public function normalizePhysicalSpec(PhysicalSpec $spec)
    {
        return array(
            'productType'     => $spec->getProductType(),
            'finishingOption' => $spec->getFinishingOption(),
            'paperClass'      => $spec->getPaperClass(),
            'packSize'        => $spec->getPackSize(),
            'paperLaminate'   => $spec->getPaperLaminate(),
        );
    }

    public function serializePhysicalSpec(PhysicalSpec $spec)
    {
        return json_encode($this->normalizePhysicalSpec($spec));
    }

    public static function denormalizePhysicalSpec($data)
    {
        return new PhysicalSpec(
            $data['productType'],
            $data['paperClass'],
            $data['finishingOption'],
            $data['packSize'],
            $data['paperLaminate']
        );
    }




}