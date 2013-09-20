<?php
namespace Moo\PackModel\Data;

use Moo\PackModel\Type\Colour;

class Box extends Data
{
    /**
     * @var Colour
     */
    protected $colour;

    const TYPE = 'boxData';

    public function __construct($linkId, Colour $colour)
    {
        parent::__construct($linkId);
        $this->colour = $colour;
    }

    public function getType()
    {
        return self::TYPE;
    }

    public function getColour()
    {
        return $this->colour;
    }
}