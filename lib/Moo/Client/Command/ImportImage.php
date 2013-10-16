<?php
namespace Moo\Client\Command;

use Guzzle\Service\Command\AbstractCommand;
use Guzzle\Service\Command\OperationCommand;
use Moo\Client\Output\ImportImageOutput;

class ImportImage extends OperationCommand
{
    protected function build()
    {
        // setup the request
        $this->request = $this->client->post();
        $query = $this->request->getQuery();
        $query->set('method', 'moo.image.importImage');
        if (isset($this['imageType'])) {
            $query->set('imageType', $this['imageType']);
        }

        if (isset($this['name'])) {
            $query->set('name', $this['name']);
        }

        if (isset($this['source'])) {
            $query->set('source', $this['source']);
        }
        
        $this->request->addPostFields(array('imageUrl' => $this['imageUrl']));

    }

    protected function process()
    {
        $this->result = ImportImageOutput::fromCommand($this);
    }
}