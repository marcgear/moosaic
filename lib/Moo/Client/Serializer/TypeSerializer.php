<?php
namespace Moo\Client\Serializer;

use Moo\PackModel\Type\Font;
use Moo\PackModel\Type\Box;
use Moo\PackModel\Type\Colour;
use Moo\PackModel\Type\CMYK;
use Moo\PackModel\Type\RGB;
use Moo\PackModel\Type\Point;

class TypeSerializer
{
    public function normalizeFont(Font $font)
    {
        return array(
            'fontFamily' => $font->getFamily(),
            'bold'   => $font->getBold(),
            'italic' => $font->getItalic(),
        );
    }
    public function denormalizeFont($data)
    {
        return new Font($data['fontFamily'], $data['bold'], $data['italic']);
    }

    public function normalizeColour(Colour $colour)
    {
        $data = array_merge(
            $colour->getValues(),
            array('type' => ($colour instanceof CMYK) ? 'CMYK' : 'RGB')
        );
        return $data;
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
             'center' => $this->normalizePoint($box->getCentre()), // because MOO can't spell
             'width'  => $box->getWidth(),
             'height' => $box->getHeight(),
             'angle'  => $box->getAngle(),
        );
    }

    public function denormalizeBox($data)
    {
        $centre = $this->denormalizePoint($data['center']);
        return new Box(
            $centre,
            $data['width'],
            $data['height'],
            $data['angle']
        );
    }

    public function normalizePoint(Point $point)
    {
        return array(
            'x' => $point->getX(),
            'y' => $point->getY(),
        );
    }

    public function denormalizePoint($data)
    {
        return new Point($data['x'], $data['y']);
    }

}