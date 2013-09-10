<?php
namespace Moo\PackModel\Data;

class Box extends Data
{
    protected $colour;

    public function getType()
    {
        return 'boxData';
    }
}