<?php
namespace Moo\PackModel\Type;

class Font
{
    protected $family;
    protected $bold;
    protected $italic;

    public function __construct($family, $bold, $italic)
    {
        $this->family = $family;
        $this->bold   = $bold;
        $this->italic = $italic;
    }

    public function getFamily()
    {
        return $this->family;
    }

    public function getBold()
    {
        return $this->bold;
    }

    public function getItalic()
    {
        return $this->italic;
    }
}