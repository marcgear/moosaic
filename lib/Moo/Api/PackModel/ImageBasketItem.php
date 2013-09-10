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

}