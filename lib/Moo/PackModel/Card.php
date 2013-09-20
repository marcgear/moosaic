<?php
namespace Moo\PackModel;

class Card
{
    /**
     * @var Int
     */
    protected $id;

    /**
     * @var Side
     */
    protected $imageSide;

    /**
     * @var Side
     */
    protected $detailsSide;

    public function __construct($id, Side $image, Side $details = null)
    {
        $this->id          = $id;
        $this->imageSide   = $image;
        $this->detailsSide = $details;
    }

    public function getId()
    {
        return $this->id;
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