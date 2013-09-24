<?php
namespace Moo\PackModel\ImageBasket;

class ImageBasketItem
{
    protected $resourceUri;
    protected $name;
    protected $source;
    protected $cacheId;
    /**
     * @var ImageBasketItemImage
     */
    protected $imageItems;

    public function __construct($resourceUri, $name, $source, $cacheId)
    {
        $this->resourceUri = $resourceUri;
        $this->name        = $name;
        $this->source      = $source;
        $this->cacheId     = $cacheId;
        $this->imageItems  = new \SplObjectStorage();
    }

    public function getResourceUri()
    {
        return $this->resourceUri;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getCacheId()
    {
        return $this->cacheId;
    }

    public function getImageItems()
    {
        $data = array();
        foreach ($this->imageItems as $item) {
            $data[] = $item;
        }
        return $data;
    }

    public function addImageItem(ImageBasketItemImage $item)
    {
        $this->imageItems->attach($item);
    }

    public function hasImageItem(ImageBasketItem $item)
    {
        return $this->imageItems->contains($item);
    }

    public function removeImageItem(ImageBasketItemImage $item)
    {
        $this->imageItems->detach($item);
    }


}