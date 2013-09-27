<?php
namespace Moosaic;
use Imagick;

class InputImage
{
    protected $_filename;
    protected $_thumb;
    protected $_colours = array();
    protected $_pixels  = array();

    public function __construct($filename)
    {
        $this->_filename = $filename;
    }

    public function getPixels()
    {
        return $this->_pixels;
    }

    public function getColours()
    {
        return $this->_colours;
    }

    public function makeThumb($output, $maxDim = 35)
    {
        if (file_exists($output)) {
            throw new Exception('Will not overwrite file');
        }
        list($width, $height) = getimagesize($this->_filename);
        $ratio = $width/$height;
        $imagick = new Imagick($this->_filename);
        if ($width > $height) {
            $height = floor(($maxDim/$ratio)*(88/59));
            $imagick->thumbnailimage($maxDim, $height);
        } else {
            $width = floor(($maxDim * $ratio)/(88/59));
            $imagick->thumbnailimage($width, $maxDim);
        }
        $imagick->writeimages($output, false);
        $this->_thumb = $output;
    }

    public function inspect()
    {
        list($width, $height) = getimagesize($this->_thumb);
        $img = imagecreatefromjpeg($this->_thumb);
        $this->_colours = array();
        $this->_pixels  = array();

        // inspect the image pixel by pixel and determine the unique colour
        // pixels and how many of each one there is
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $colour = $this->_intToHex(imagecolorat($img, $x, $y));
                $this->_pixels[$y][$x] = $colour;
                if (isset($this->_colours[$colour])) {
                    $this->_colours[$colour]++;
                } else {
                    $this->_colours[$colour] = 1;
                }
            }
        }
    }

    protected function _intToHex($rgb)
    {
        $r = str_pad(dechex(($rgb >> 16) & 0xFF), 2, '0', STR_PAD_LEFT);
        $g = str_pad(dechex(($rgb >> 8) & 0xFF),  2, '0', STR_PAD_LEFT);
        $b = str_pad(dechex($rgb & 0xFF),         2, '0', STR_PAD_LEFT);
        $hex = $r.$g.$b;
        return $hex;
    }


}
