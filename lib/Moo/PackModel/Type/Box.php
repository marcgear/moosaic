<?php
namespace Moo\PackModel\Type;

class Box
{
    protected $centre;
    protected $width;
    protected $height;
    protected $angle;

    public function __construct(Point $centre, $width, $height, $angle)
    {
        $this->centre = $centre;
        $this->width  = $width;
        $this->height = $height;
        $this->angle  = $angle;
    }

    public function getCentre()
    {
        return $this->centre;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getAngle()
    {
        return $this->angle;
    }
}