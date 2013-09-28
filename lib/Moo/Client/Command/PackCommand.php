<?php
namespace Moo\Client\Command;

use Guzzle\Service\Command\OperationCommand;
use Moo\Client\Output\PackMethodOutput;

class PackCommand extends OperationCommand
{
    public function process()
    {
        $this->result = PackMethodOutput::fromCommand($this);
    }
}