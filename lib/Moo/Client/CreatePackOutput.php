<?php
namespace Moo\Client;

use Guzzle\Service\Command\OperationCommand;
use Guzzle\Service\Command\ResponseClassInterface;
use JMS\Serializer\SerializerBuilder;
use Moo\Client\Serializer\DataSerializer;
use Moo\Client\Serializer\PackModelSerializer;
use Moo\Client\Serializer\Serializer;
use Moo\Client\Serializer\TypeSerializer;
use Moo\PackModel\Pack;

class CreatePackOutput implements ResponseClassInterface
{
    public static function fromCommand(OperationCommand $command)
    {
        $response = $command->getResponse()->json();
        $serializer = new PackModelSerializer(new DataSerializer(new TypeSerializer()));
        $pack = $serializer->deserializePack($response['packId'], $response['pack']);
    }
}