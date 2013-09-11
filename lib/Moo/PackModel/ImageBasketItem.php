<?php
namespace Moo\PackModel;

class ImageBasketItem
{
    protected $resourceUri;
    protected $name;
    protected $source;
    protected $cacheId;

    /**
     * @var ImageBasketItemImage
     */
    protected $imageItems = array();

    public function __construct($resourceUri, $name, $source, $cacheId)
    {
        $this->resourceUri = $resourceUri;
        $this->name        = $name;
        $this->source      = $source;
        $this->cacheId     = $cacheId;
    }

}