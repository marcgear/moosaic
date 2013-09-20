<?php
namespace Moo\Client\Serializer;

use Moo\PackModel\Card;
use Moo\PackModel\Extra;
use Moo\PackModel\ImageBasket;
use Moo\PackModel\ImageBasketItem;
use Moo\PackModel\ImageBasketItemImage;
use Moo\PackModel\Pack;
use Moo\PackModel\Side;

class PackModelSerializer
{
    protected $dataSerializer;

    public function __construct(DataSerializer $dataSerializer)
    {
        $this->dataSerializer = $dataSerializer;
    }

    public function serializePack(Pack $pack)
    {
        $sides  = array();
        $extras = array();

        foreach ($pack->getSides() as $side) {
            $sides[] = $this->serializeSide($side);
        }

        foreach ($pack->getExtras() as $extra) {
            $extras[] = $this->serializeExtra($extra);
        }

        return json_encode(
            array(
                 'productCode' => $pack->getProductCode(),
                 'numCards'    => $pack->getNumCards(),
                 'sides'       => $sides,
                 'extras'      => $extras,
                 'imageBasket' => $this->serializeImageBasket($pack->getImageBasket()),
            )
        );
    }

    public function deserializePack($id, $packData)
    {
        $pack = new Pack($id);
        $pack->setProductCode($packData['productCode']);
        $pack->setNumCards($packData['numCards']);
        foreach ($packData['sides'] as $sideData) {
            $pack->addSide($this->deserializeSide($sideData));
        }
        foreach ($packData['cards'] as $cardData) {
            $pack->addCard($this->deserializeCard($cardData, $pack->getSides()));
        }
        foreach ($packData['extras'] as $extraData) {
            $pack->addExtra($this->deserializeExtra($extraData));
        }
        foreach ($packData['imageBasket']['items'] as $item) {
            $pack->getImageBasket()->addItem($this->deserializeImageBasketItem($item));
        }
        return $pack;
    }

    public function serializeSide(Side $side)
    {
        $datae = array();
        foreach ($side->getData() as $data) {
            $datae[] = $this->dataSerializer->serializeData($data);
        }

        return json_encode(
            array(
                 'type'         => $side->getType(),
                 'sideNum'      => $side->getSideNum(),
                 'templateCode' => $side->getTemplateCode(),
                 'data'         => $datae,
            )
        );
    }

    public function deserializeSide($sideData)
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

    public function deserializeCard($cardData, $packSides)
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

    public function serializeExtra(Extra $extra)
    {
        return json_encode(
            array(
                'key'   => $extra->getKey(),
                'value' => $extra->getValue(),
            )
        );
    }

    public function deserializeExtra($extraData)
    {
        return new Extra($extraData['key'], $extraData['value']);
    }

    public function serializeImageBasket(ImageBasket $imageBasket)
    {
        $items = array();
        foreach ($imageBasket->getItems() as $item) {
            $items[] = $this->serializeImageBasketItem($item);
        }

        return json_encode(
            array(
                 'items' => $items,
            )
        );
    }

    public function deserializeImageBasket($data)
    {
        $basket =  new ImageBasket();
        foreach ($data['items'] => $itemData) {
            $basket->addItem($this->deserializeImageBasketItem($data));
        }
        return $basket;
    }

    public function serializeImageBasketItem(ImageBasketItem $item)
    {
        $imageItems = array();
        foreach ($item->getImageItems() as $imageItem){
            $imageItems[] = $this->serializeImageBasketItem($imageItem);
        }

        return json_encode(
            array(
                 'resourceUri' => $item->getResourceUri(),
                 'name'        => $item->getName(),
                 'source'      => $item->getSource(),
                 'cacheId'     => $item->getCacheId(),
                 'imageItems'  => $imageItems,
            )
        );
    }

    public function deserializeImageBasketItem($data)
    {
        $item = new ImageBasketItem(
            $data['resourceUri'],
            $data['name'],
            $data['source'],
            $data['cacheId']
        );
        foreach ($data['imageItems'] as $imageItem) {
            $item->addImageItem($this->deserializeImageBasketItemImage($imageItem));
        }
        return $item;
    }

    public function serializeImageBasketItemImage(ImageBasketItemImage $image)
    {
        return json_encode(
            array(
                 'type'        => $image->getType(),
                 'resourceUri' => $image->getResourceUri(),
                 'width'       => $image->getWidth(),
                 'height'      => $image->getHeight(),
                 'rotation'    => $image->getRotation(),
            )
        );
    }

    public function deserializeImageBasketItemImage($data)
    {
        return new ImageBasketItemImage(
            $data['type'],
            $data['resourceUri'],
            $data['width'],
            $data['height'],
            $data['rotation']
        );
    }




}