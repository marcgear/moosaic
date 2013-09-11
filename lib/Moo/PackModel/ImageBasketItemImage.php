<?php
namespace Moo\PackModel;

class ImageBasketItemImage
{
    protected $type;
    protected $resourceUri;
    protected $width;
    protected $height;
    protected $rotation;

    public function __construct($type, $resourceUri, $width, $height, $rotation)
    {
        $this->type        = $type;
        $this->resourceUri = $resourceUri;
        $this->width       = $width;
        $this->height      = $height;
        $this->rotation    = $rotation;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getResourceUri()
    {
        return $this->resourceUri;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getRotation()
    {
        return $this->rotation;
    }
}