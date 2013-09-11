<?php
namespace Moo\PackModel\Type;

class RGB implements Colour
{
    protected $r;
    protected $g;
    protected $b;

    public function __construct($r, $g, $b)
    {
        $this->r = $r;
        $this->g = $g;
        $this->b = $b;
    }

    public function getValues()
    {
        return array(
            'r' => $this->r,
            'g' => $this->g,
            'b' => $this->b,
        );
    }

}