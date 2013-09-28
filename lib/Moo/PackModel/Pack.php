<?php
namespace Moo\PackModel;

use Moo\PackModel\ImageBasket\ImageBasket;

class Pack
{
    protected $id;
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

    public function __construct($id = null)
    {
        $this->id          = $id;
        $this->imageBasket = new ImageBasket();
        $this->sides       = new \SplObjectStorage();
        $this->cards       = new \SplObjectStorage();
        $this->extras      = new \SplObjectStorage();
    }

    public function getId()
    {
        return $this->id;
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
            $sides[$side->getType().'-'.$side->getSideNum()] = $side;
        }
        return $sides;
    }

    public function getCards()
    {
        $cards = array();
        foreach ($this->cards as $card) {
            $cards[] = $card;
        }
        return $cards;
    }

    public function getExtras()
    {
        $extras = array();
        foreach ($this->extras as $extras) {
            $extra[] = $extras;
        }
        return $extras;
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
        $this->sides->detach($side);
    }

    public function addCard(Card $card)
    {
        $this->cards->attach($card);
        if (!$this->hasSide($card->getImageSide())) {
            $this->addSide($card->getImageSide());
        }
        if (!$this->hasSide($card->getDetailsSide())) {
            $this->addSide($card->getDetailsSide());
        }
    }

    public function hasCard(Card $card)
    {
        return $this->cards->contains($card);
    }

    public function removeCard(Card $card)
    {
        $this->cards->detach($card);
    }

    public function addExtra(Extra $extra)
    {
        $this->extras->attach($extra);
    }

    public function hasExtra(Extra $extra)
    {
        return $this->extras->contains($extra);
    }

    public function removeExtra(Extra $extra)
    {
        $this->extras->detach($extra);
    }
}