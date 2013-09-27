<?php
namespace Moo\PackModel\Data;

use \Moo\PackModel\Type\Font;
use \Moo\PackModel\Type\Colour;


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

    /**
     * @var int
     */
    protected $pointSize;

    /**
     * @var String
     */
    protected $alignment;

    const TYPE = 'textData';

    const ALIGN_LEFT   = 'left';
    const ALIGN_RIGHT  = 'right';
    const ALIGN_CENTER = 'center';


    public function __construct($linkId, $text, Font $font, Colour $colour, $pointSize, $alignment)
    {
        parent::__construct($linkId);
        $this->text      = $text;
        $this->font      = $font;
        $this->colour    = $colour;
        $this->pointSize = $pointSize;
        $this->alignment = $alignment;
    }

    public function getType()
    {
        return self::TYPE;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getFont()
    {
        return $this->font;
    }

    public function getColour()
    {
        return $this->colour;
    }

    public function getPointSize()
    {
        return $this->pointSize;
    }

    public function getAlignment()
    {
        return $this->alignment;
    }
}