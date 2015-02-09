<?php
namespace Moo\PackModel\Type;

class Point
{
    protected $x;
    protected $y;

    public function __construct($x, $y)
    {
        $this->x      = $x;
        $this->y      = $y;
    }

    public function getX()
    {
        return $this->x;
    }

    public function getY()
    {
        return $this->y;
    }
}