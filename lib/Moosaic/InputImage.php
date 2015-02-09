<?php
namespace Moosaic;
use Imagick;

class InputImage
{
    protected $filename;
    protected $thumb;
    protected $colours = array();
    protected $pixels  = array();

    public function __construct($filename, $type)
    {
        $this->filename = $filename;

        switch ($type) {
            case 'bc-l':
                $this->cardDim['width']  = 88;
                $this->cardDim['height'] = 59;
                $this->thumbDim['width'] = 22;
                $this->thumbDim['height'] = 15;
                break;
            case 'bc-p':
                $this->cardDim['width']   = 59;
                $this->cardDim['height']  = 88;
                $this->thumbDim['width']  = 13;
                $this->thumbDim['height'] = 22;
                break;
            case 'mc-l':
                 $this->cardDim['width']   = 70;
                 $this->cardDim['height']  = 28;
                 $this->thumbDim['width']  = 18;
                 $this->thumbDim['height'] = 7;
                 break;
            case 'mc-p':
                $this->cardDim['width']   = 28;
                $this->cardDim['height']  = 70;
                $this->thumbDim['width']  = 7;
                $this->thumbDim['height'] = 22;
                break;
            case 'sq';
                $this->cardDim['width']  = 65;
                $this->cardDim['height'] = 65;
                $this->thumbDim['width']  = 15;
                $this->thumbDim['height'] = 16;
                break;
        }
    }

    public function getPixels()
    {
        return $this->pixels;
    }

    public function getColours()
    {
        return $this->colours;
    }

    public function makeThumb($output, $maxDim = 2000)
    {
        if (file_exists($output)) {
            throw new Exception('Will not overwrite file');
        }
        list($width, $height) = getimagesize($this->filename);
        $ratio = $width/$height;

        if ($width > $height) {
            $maxCards = floor($maxDim / $this->cardDim['width']);
        } else {
            $maxCards = floor($maxDim / $this->cardDim['height']);
        }

        $imagick = new Imagick($this->filename);
        if ($width > $height) {
            $height = floor(($maxCards/$ratio)*($this->cardDim['width']/$this->cardDim['height']));
            $imagick->thumbnailimage($maxCards, $height);
        } else {
            $width = floor(($maxCards * $ratio)/($this->cardDim['width']/$this->cardDim['height']));
            $imagick->thumbnailimage($width, $maxCards);
        }
        $imagick->writeimages($output, false);
        $this->thumb = $output;
    }

    public function inspect()
    {
        list($width, $height) = getimagesize($this->thumb);
        $img = imagecreatefromjpeg($this->thumb);
        $this->colours = array();
        $this->pixels  = array();

        // inspect the image pixel by pixel and determine the unique colour
        // pixels and how many of each one there is
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $colour = $this->intToHex(imagecolorat($img, $x, $y));
                $this->pixels[$y][$x] = $colour;
                if (isset($this->colours[$colour])) {
                    $this->colours[$colour]++;
                } else {
                    $this->colours[$colour] = 1;
                }
            }
        }
    }

    protected function intToHex($rgb)
    {
        $r   = str_pad(dechex(($rgb >> 16) & 0xFF), 2, '0', STR_PAD_LEFT);
        $g   = str_pad(dechex(($rgb >> 8) & 0xFF),  2, '0', STR_PAD_LEFT);
        $b   = str_pad(dechex($rgb & 0xFF),         2, '0', STR_PAD_LEFT);
        $hex = $r.$g.$b;
        return $hex;
    }

    public function draw()
    {?>
        <table border=0 cellpadding="1" cellspacing="1">
        <?php
        foreach ($this->getPixels() as $pixels) {
            ?>
            <tr>
                <?php foreach ($pixels as $pixel) {?>
                    <td width="<?php echo $this->thumbDim['width'];?>" height="<?php echo $this->thumbDim['height'];?>" style="background-color:<?php echo $pixel;?>;"><img src="<?php if (isset($images[$pixel][0])) { echo $images[$pixel][0]; }?>"</td>
                <?php } ?>
            </tr>
        <?php } ?>
        </table>
        <?php
    }
}
