<?php
namespace Moo\PackModel\Data;

use Moo\PackModel\Type\Colour;

class Box extends Data
{
    /**
     * @var Colour
     */
    protected $colour;

    public function __construct($linkId, Colour $colour)
    {
        parent::__construct($linkId);
        $this->colour = $colour;
    }

    public function getType()
    {
        return 'boxData';
    }

    public function getColour()
    {
        return $this->colour;
    }
}