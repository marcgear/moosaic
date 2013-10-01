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
    /**
     * @var PackFactory
     */
    protected $factory;

    /**
     * @var Pack
     */
    protected $currentPack;

    protected $packs = array();

    public function __construct(PackFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return Pack[]
     */
    public function getPacks()
    {
        return $this->packs;
    }

    public function addCard(Colour $colour, $image, $x, $y)
    {
        if (!$this->currentPack || count($this->currentPack->getCards()) == $this->currentPack->getNumCards()) {
            $this->createNewPack();
        }
        $cardNum     = count($this->currentPack->getCards()) + 1;
        $imageSide   = $this->createImageSide($colour, $image);
        $detailsSide = $this->createDetailsSide($x, $y);
        $card        = new Card($cardNum, $imageSide, $detailsSide);

        $this->currentPack->addCard($card);
    }

    /**
     * @param Colour $colour
     * @param        $image
     *
     * @return Side
     */
    protected function createImageSide(Colour $colour, $image)
    {
        $sideNum = count($this->currentPack->getSides()) + 1;
        $side    = new Side(Side::TYPE_IMAGE, $sideNum, 'businesscard_full_image_landscape');
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
        $line2   = 'x = '.$x;
        $line3   = 'y = '.$y;

        $sideNum = count($this->currentPack->getSides()) + 1;
        $side    = new Side(Side::TYPE_DETAILS, $sideNum, 'businesscard_full_text_landscape');
        $font    = new Font('bryant', false, false);
        $colour  = new CMYK(0, 0, 0, 100);
        $side->addData(new Text('back_line_2', $line2, $font, $colour, 4, Text::ALIGN_LEFT));
        $side->addData(new Text('back_line_3', $line3, $font, $colour, 4, Text::ALIGN_LEFT));

        return $side;
    }

    /**
     * create a new pack, remember it as the current pack and add it to the array of packs built
     */
    protected function createNewPack()
    {
        $pack = $this->factory->createPack();

        $this->packs[$pack->getId()] = $pack;
        $this->currentPack           = $pack;
    }


}