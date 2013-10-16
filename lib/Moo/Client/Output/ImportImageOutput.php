<?php
namespace Moo\Client\Output;

use Guzzle\Service\Command\OperationCommand;
use Guzzle\Service\Command\ResponseClassInterface;
use Moo\PackModel\ImageBasket\ImageBasketItem;

class ImportImageOutput implements ResponseClassInterface
{
    /**
     * @var \Moo\PackModel\ImageBasket\ImageBasketItem
     */
    protected $imageBasketItem;

    protected $warnings;

    public function __construct(ImageBasketItem $imageBasketItem, $warnings = array())
    {
        $this->imageBasketItem = $imageBasketItem;
        $this->warnings        = $warnings;
    }

    public static function fromCommand(OperationCommand $command)
    {
        $response = $command->getResponse()->json();
        $item     = $command['serializer']->denormalizeImageBasketItem($response['imageBasketItem']);
        return new self($item, $response['warnings']);

    }

    public function getImageBasketItem()
    {
        return $this->imageBasketItem;
    }

    public function getWarnings()
    {
        return $this->warnings;
    }


}