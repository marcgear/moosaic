<?php
namespace Moo\PackModel;

class Pack
{
    protected $numCards;
    protected $productCode;

    /**
     * @var \SplObjectStorage
     */
    protected $sides;

    /**
     * @var Card[]
     */
    protected $cards = array();

    /**
     * @var Extra[]
     */
    protected $extras = array();

    /**
     * @var ImageBasket
     */
    protected $imageBasket;

    public function __construct()
    {
        $this->imageBasket = new ImageBasket();
        $this->sides = new \SplObjectStorage();
    }

    public function getNumCards()
    {
        return $this->numCards;
    }

    public function getProductCode()
    {
        return $this->productCode;
    }

    public function getSides()
    {
        $sides = array();
        foreach ($this->sides as $side) {
            $sides[] = $side;
        }
        return $sides;
    }

    public function getCards()
    {
        return $this->cards;
    }

    public function getExtras()
    {
        return $this->extras;
    }

    public function getImageBasket()
    {
        return $this->imageBasket;
    }

    public function setNumCards($num)
    {
        $this->numCards = $num;
    }

    public function setProductCode($code)
    {
        $this->productCode = $code;
    }

    public function addSide(Side $side)
    {
        $this->sides->attach($side);
    }

    public function hasSide(Side $side)
    {
        return $this->sides->contains($side);
    }

    public function removeSide(Side $side)
    {
        // remove the sides
        $this->sides->detach($side);
    }
}