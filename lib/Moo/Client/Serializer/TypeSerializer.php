<?php
namespace Moo\Client\Serializer;

use Moo\PackModel\Type\Font;
use Moo\PackModel\Type\Box;
use Moo\PackModel\Type\Colour;
use Moo\PackModel\Type\CMYK;
use Moo\PackModel\Type\RGB;

class TypeSerializer
{
    public function serializeFont(Font $font)
    {
        return array(
            'family' => $font->getFamily(),
            'bold'   => $font->getBold(),
            'italic' => $font->getItalic(),
        );
    }
    public function denormalizeFont($data)
    {
        return new Font($data['family'], $data['bold'], $data['italic']);
    }

    public function normalizeColour(Colour $colour)
    {
        $data = array_merge(
            $colour->getValues(),
            array('type' => get_class($colour) == 'CMYK' ? 'CMYK' : 'RGB')
        );
        return json_encode($data);
    }

    public function denormalizeColour($data)
    {
        $colour = null;
        switch ($data['type']) {
            case 'CMYK':
                $colour = new CMYK($data['c'], $data['m'], $data['y'], $data['k']);
                break;
            case 'RGB':
                $colour = new RGB($data['r'], $data['g'], $data['b']);
                break;
        }
        return $colour;
    }

    public function normalizeBox(Box $box)
    {
        return array(
             'x'      => $box->getX(),
             'y'      => $box->getY(),
             'width'  => $box->getWidth(),
             'height' => $box->getHeight(),
             'angle'  => $box->getAngle(),
        );
    }

    public function denormalizeBox($data)
    {
        return new Box($data['x'], $data['y'], $data['width'], $data['height'], $data['angle']);
    }

}