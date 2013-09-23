<?php
namespace Moo\Client;

use Guzzle\Service\Command\OperationCommand;
use Guzzle\Service\Command\ResponseClassInterface;
use Moo\Client\Serializer\DataSerializer;
use Moo\Client\Serializer\PackModelSerializer;
use Moo\Client\Serializer\TypeSerializer;
use Moo\PackModel\PhysicalSpec;

class PhysicalSpecOutput implements ResponseClassInterface
{
    /**
     * @var int
     */
    protected $packId;

    /**
     * @var \Moo\PackModel\PhysicalSpec
     */
    protected $physicalSpec;

    public function __construct($packId, PhysicalSpec $physicalSpec)
    {
        $this->packId       = $packId;
        $this->physicalSpec = $physicalSpec;
    }

    public static function fromCommand(OperationCommand $command)
    {
        $response   = $command->getResponse()->json();
        $spec = $command['serializer']->denormalizePhysicalSpec($response['physicalSpec']);
        return new self($response['packId'], $spec);
    }

    public function getPackId()
    {
        return $this->packId;
    }

    public function getPhysicalSpec()
    {
        return $this->physicalSpec;
    }
}