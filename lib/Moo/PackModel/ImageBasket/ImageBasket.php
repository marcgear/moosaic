<?php
namespace Moo\PackModel\ImageBasket;

class ImageBasket
{
    /**
     * @var \SplObjectStorage
     */
    protected $items;

    public function __construct()
    {
        $this->items = new \SplObjectStorage();
    }

    public function getItems()
    {
        $items = array();
        foreach ($this->items as $item) {
            $items[] = $item;
        }
        return $items;
    }

    public function addItem(ImageBasketItem $item)
    {
        $this->items->attach($item);
    }

    public function hasItem(ImageBasketItem $item)
    {
        return $this->$item->contains($item);
    }

    public function removeItem(ImageBasketItem $item)
    {
        $this->items->detach($item);
    }
}
