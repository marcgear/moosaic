<?php
namespace Moo\Client\Serializer;

use Moo\PackModel\Data\Data;
use Moo\PackModel\Data\Image;
use Moo\PackModel\Data\MultiLineText;
use Moo\PackModel\Data\Text;
use Moo\PackModel\Data\Box;

class DataSerializer
{
    /**
     * @var TypeSerializer
     */
    protected $typeSerializer;

    public function __construct(TypeSerializer $typeSerializer)
    {
        $this->typeSerializer = $typeSerializer;
    }

    public function normalizeData(Data $data)
    {
        $out = array();
        switch ($data->getType()) {
            case Text::TYPE:
            case MultiLineText::TYPE:
                $out = $this->normalizeText($data);
                break;
            case Image::TYPE:
                $out = $this->normalizeImage($data);
                break;
            case Box::TYPE:
                $out = $this->normalizeBox($data);
        }
        return $out;
    }

    public function denormalizeData($data)
    {
        $obj = null;
        switch ($data['type']) {
            case Text::TYPE:
                $obj = $this->denormalizeText($data);
                break;
            case MultiLineText::TYPE:
                $obj = $this->denormalizeMultiLineText($data);
                break;
            case Image::TYPE:
                $obj = $this->denormalizeImage($data);
                break;
            case Box::TYPE:
                $obj = $this->denormalizeBox($data);
                break;
        }
        return $obj;
    }

    public function normalizeText(Text $text)
    {
        return array(
             'linkId'    => $text->getLinkId(),
             'type'      => $text->getType(),
             'text'      => $text->getText(),
             'font'      => $this->typeSerializer->normalizeFont($text->getFont()),
             'colour'    => $this->typeSerializer->normalizeColour($text->getColour()),
             'pointSize' => $text->getPointSize(),
             'alignment' => $text->getAlignment(),
        );
    }

    public function denormalizeText($data)
    {
        $text = new Text(
            $data['linkId'],
            $data['text'],
            $this->typeSerializer->denormalizeFont($data['font']),
            $this->typeSerializer->denormalizeColour($data['colour']),
            $data['pointSize'],
            $data['alignment']
        );
        return $text;
    }

    public function serializeMultiLineText(MultiLineText $text)
    {
        return $this->normalizeText($text);
    }

    public function denormalizeMultiLineText($data)
    {
        $text = new MultiLineText(
            $data['linkId'],
            $data['text'],
            $this->typeSerializer->denormalizeFont($data['font']),
            $this->typeSerializer->denormalizeColour($data['colour']),
            $data['pointSize'],
            $data['alignment']
        );
        return $text;
    }

    public function normalizeImage(Image $image)
    {
        return array(
             'linkId'           => $image->getLinkId(),
             'type'             => $image->getType(),
             'imageBox'         => $this->typeSerializer->normalizeBox($image->getImageBox()),
             'resourceUri'      => $image->getResourceUri(),
             'imageStoreFileId' => $image->getImageStoreFileId(),
             'enhance'          => $image->getEnhance(),
        );
    }

    public function denormalizeImage($data)
    {
        $image = new Image(
            $data['linkId'],
            $this->typeSerializer->denormalizeBox($data['imageBox']),
            $data['resourceUri'],
            $data['imageStoreFileId'],
            $data['enhance']
        );
        return $image;
    }

    public function normalizeBox(Box $box)
    {
        return array(
            'linkId' => $box->getLinkId(),
            'type'   => $box->getType(),
            'colour' => $this->typeSerializer->normalizeColour($box->getColour()),
        );
    }

    public function denormalizeBox($data)
    {
        return new Box(
            $data['linkId'],
            $this->typeSerializer->denormalizeColour($data['colour'])
        );
    }
}