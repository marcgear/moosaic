<?php
namespace Moo\PackModel;

class Card
{
    /**
     * @var Side
     */
    protected $imageSide;

    /**
     * @var Side
     */
    protected $detailsSide;

    public function __construct(Side $image, Side $details = null)
    {
        $this->imageSide   = $image;
        $this->detailsSide = $details;
    }

    public function getImageSide()
    {
        return $this->imageSide;
    }

    public function getDetailsSide()
    {
        return $this->detailsSide;
    }
}