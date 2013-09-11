<?php
namespace Moo\PackModel\Type;

class Box
{
    protected $x;
    protected $y;
    protected $width;
    protected $height;
    protected $angle;

    public function __construct($x, $y, $width, $height, $angle)
    {
        $this->x      = $x;
        $this->y      = $y;
        $this->width  = $width;
        $this->height = $height;
        $this->angle  = $angle;
    }

    public function getX()
    {
        return $this->x;
    }

    public function getY()
    {
        return $this->y;
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