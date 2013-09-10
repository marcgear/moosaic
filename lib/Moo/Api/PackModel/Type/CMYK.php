<?php
namespace Moo\PackModel\Type;

class CMYK implements Colour
{
    protected $c;
    protected $m;
    protected $y;
    protected $k;

    public function __construct($c, $m, $y, $k)
    {
        $this->c = $c;
        $this->m = $m;
        $this->y = $y;
        $this->k = $k;
    }

}