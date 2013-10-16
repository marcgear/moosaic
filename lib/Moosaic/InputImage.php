<?php
namespace Moosaic;
use Imagick;

class InputImage
{
    protected $filename;
    protected $thumb;
    protected $colours = array();
    protected $pixels  = array();

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function getPixels()
    {
        return $this->pixels;
    }

    public function getColours()
    {
        return $this->colours;
    }

    public function makeThumb($output, $maxDim = 35)
    {
        if (file_exists($output)) {
            throw new Exception('Will not overwrite file');
        }
        list($width, $height) = getimagesize($this->filename);
        $ratio = $width/$height;
        $imagick = new Imagick($this->filename);
        if ($width > $height) {
            $height = floor(($maxDim/$ratio)*(88/59));
            $imagick->thumbnailimage($maxDim, $height);
        } else {
            $width = floor(($maxDim * $ratio)/(88/59));
            $imagick->thumbnailimage($width, $maxDim);
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
        <table border=0>
        <?php
        foreach ($this->getPixels() as $pixels) {
            ?>
            <tr>
                <?php foreach ($pixels as $pixel) {?>
                    <td width="22" height="16" style="background-color:<?php echo $pixel;?>;"><img src="<?php if (isset($images[$pixel][0])) { echo $images[$pixel][0]; }?>"</td>
                <?php } ?>
            </tr>
        <?php } ?>
        </table>
        <?php
    }
}
