<?php
namespace Moo\PackModel\Data;

class Text extends Data
{
    protected $text;

    /**
     * @var \Moo\PackModel\Type\Font
     */
    protected $font;

    /**
     * @var \Moo\PackModel\Type\Colour
     */
    protected $colour;
    protected $pointSize;
    protected $alignment;

    public function getType()
    {
        return 'textData';
    }
}