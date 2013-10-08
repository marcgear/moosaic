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


    public function __construct(ImageBasketItem $imageBasketItem)
    {
        $this->imageBasketItem = $imageBasketItem;
    }

    public static function fromCommand(OperationCommand $command)
    {
        $response = $command->getResponse()->json();
        echo 'DEBUG ON LINE ',__LINE__, ' in ', __FILE__, "\n<pre>\n";
        print_r($response);
        echo "\n</pre>\n";
        exit;
        return new self($pack);

    }

}