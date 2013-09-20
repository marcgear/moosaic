<?php
namespace Moo\Client\Serializer;

use Moo\PackModel\Data\Data;
use Moo\PackModel\Data\Image;
use Moo\PackModel\Data\MultiLineText;
use Moo\PackModel\Data\Text;
use Moo\PackModel\Data\Box;
use Weasel\Annotation\Tests\Multi;

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

    public function serializeData(Data $data)
    {
        $str = null;
        switch ($data->getType()) {
            case Text::TYPE:
            case MultiLineText::TYPE:
                $str = $this->serializeText($data);
                break;
            case Image::TYPE:
                $str = $this->serializeImage($data);
                break;
            case Box::TYPE:
                $str = $this->serializeBox($data);
        }
        return $str;
    }

    public function deserializeData($data)
    {
        $obj = null;
        switch ($data['type']) {
            case Text::TYPE:
                $obj = $this->deserializeText($data);
                break;
            case MultiLineText::TYPE:
                $obj = $this->deserializeMultiLineText($data);
                break;
            case Image::TYPE:
                $obj = $this->deserializeImage($data);
                break;
            case Box::TYPE:
                $obj = $this->deserializeImage($data);
                break;
        }
        return $obj;
    }

    public function serializeText(Text $text)
    {
        return json_encode(
            array(
                 'linkId'    => $text->getLinkId(),
                 'type'      => $text->getType(),
                 'font'      => $this->typeSerializer->serializeFont($text->getFont()),
                 'colour'    => $this->typeSerializer->serializeColour($text->getColour()),
                 'pointSize' => $text->getPointSize(),
                 'alignment' => $text->getAlignment(),
            )
        );
    }

    public function deserializeText($data)
    {
        $text = new Text(
            $data['linkId'],
            $data['text'],
            $this->typeSerializer->deserializeFont($data['font']),
            $this->typeSerializer->deserializeColour($data['colour']),
            $data['pointSize'],
            $data['alignment']
        );
        return $text;
    }

    public function serializeMultiLineText(MultiLineText $text)
    {
        return $this->serializeText($text);
    }

    public function deserializeMultiLineText($data)
    {
        $text = new MultiLineText(
            $data['linkId'],
            $data['text'],
            $this->typeSerializer->deserializeFont($data['font']),
            $this->typeSerializer->deserializeColour($data['colour']),
            $data['pointSize'],
            $data['alignment']
        );
        return $text;
    }

    public function serializeImage(Image $image)
    {
        return json_encode(
            array(
                 'linkId'           => $image->getLinkId(),
                 'type'             => $image->getType(),
                 'imageBox'         => $this->typeSerializer->serializeBox($image->getImageBox()),
                 'resourceUri'      => $image->getResourceUri(),
                 'imageStoreFileId' => $image->getImageStoreFileId(),
                 'enhance'          => $image->getEnhance(),
            )
        );
    }

    public function deserializeImage($data)
    {
        $image = new Image(
            $data['linkId'],
            $this->typeSerializer->deserializeBox($data['imageBox']),
            $data['resourceUri'],
            $data['imageStoreFileId'],
            $data['enhance']
        );
        return $image;
    }

    public function serializeBox(Box $box)
    {
        return json_encode(
            array(
                'linkId' => $box->getLinkId(),
                'type'   => $box->getType(),
                'colour' => $this->typeSerializer->deserializeColour($box->getColour()),
            )
        );
    }

    public function deserializeBox($data)
    {
        return new Box(
            $data['linkId'],
            $this->typeSerializer->deserializeColour($data['colour'])
        );
    }
}