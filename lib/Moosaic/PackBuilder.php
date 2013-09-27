<?php
namespace Moosaic;

use Moo\PackModel\Card;
use Moo\PackModel\Pack;
use Moo\PackModel\Side;
use Moo\PackModel\Data\Box;
use Moo\PackModel\Data\Image;
use Moo\PackModel\Data\Text;
use Moo\PackModel\Type\CMYK;
use Moo\PackModel\Type\Colour;
use Moo\PackModel\Type\Font;

class PackBuilder
{
    protected $pack;

    public function __construct(Pack $pack)
    {
        $this->pack = $pack;
    }

    /**
     * @return Pack
     */
    public function getPack()
    {
        return $this->pack;
    }

    public function addCard(Colour $colour, $image, $x, $y)
    {
        $cardNum     = count($this->pack->getCards()) + 1;
        $imageSide   = $this->createImageSide($colour, $image);
        $detailsSide = $this->createDetailsSide($x, $y);
        $card        = new Card($cardNum, $imageSide, $detailsSide);

        $this->pack->addCard($card);
    }

    /**
     * @param Colour $colour
     * @param        $image
     *
     * @return Side
     */
    protected function createImageSide(Colour $colour, $image)
    {
        $sideNum = count($this->pack->getSides()) + 1;
        $side    = new Side(Side::TYPE_IMAGE, $sideNum, 'businesscard_full_image');
        $data    = new Box('background_box', $colour);
        $side->addData($data);
        return $side;
    }

    /**
     * @param $position
     *
     * @return Side
     */
    protected function createDetailsSide($x, $y)
    {
        $line2     = 'x = '.$x;
        $line3     = 'y = '.$y;

        $sideNum = count($this->pack->getSides()) + 1;
        $side    = new Side(Side::TYPE_IMAGE, $sideNum, 'businesscard_full_text_landscape');
        $font    = new Font('bryant', false, false);
        $colour  = new CMYK(0, 0, 0, 100);
        $side->addData(new Text('back_line_2', $line2, $font, $colour, 8, Text::ALIGN_LEFT));
        $side->addData(new Text('back_line_3', $line3, $font, $colour, 8, Text::ALIGN_LEFT));

        return $side;
    }


}