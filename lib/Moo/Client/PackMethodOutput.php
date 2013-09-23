<?php
namespace Moo\Client;

use Guzzle\Service\Command\OperationCommand;
use Guzzle\Service\Command\ResponseClassInterface;
use Moo\Client\Serializer\PackModelSerializer;
use Moo\PackModel\Pack;
use Moo\PackModel\PhysicalSpec;

class PackMethodOutput implements ResponseClassInterface
{
    /**
     * @var array
     */
    protected $warnings;

    /**
     * @var array
     */
    protected $dropIns;

    /**
     * @var \Moo\PackModel\Pack
     */
    protected $pack;

    /**
     * @var \Moo\PackModel\PhysicalSpec
     */
    protected $physicalSpec;

    public function __construct(Pack $pack, $warnings = array(), $dropIns = array(), PhysicalSpec $physicalSpec = null)
    {
        $this->pack         = $pack;
        $this->warnings     = $warnings;
        $this->dropIns      = $dropIns;
        $this->physicalSpec = $physicalSpec;
    }

    public static function fromCommand(OperationCommand $command)
    {
        $response = $command->getResponse()->json();
        $pack     = $command['serializer']->denormalizePack($response['packId'], $response['pack']);

        if (isset($response['physicalSpec'])) {
            $spec = $command['serializer']->denormalizePhysicalSpec($response['physicalSpec']);
        } else {
            $spec = null;
        }
        return new self($pack, $response['warnings'], $response['dropIns'], $spec);

    }

    public function getPack()
    {
        return $this->pack;
    }

    public function getWarnings()
    {
        return $this->warnings;
    }

    public function getDropIns()
    {
        return $this->dropIns;
    }

    public function getPhysicalSpec()
    {
        return $this->physicalSpec;
    }
}