<?php
namespace Moo\Client\Command;

use Guzzle\Service\Command\AbstractCommand;

class UpdatePack extends AbstractCommand
{
    protected function build()
    {
        // setup the request
        $this->request = $this->client->post();
        $query = $this->request->getQuery();
        $query->set('method', 'moo.pack.updatePack');
        $query->set('packId', $this['pack']->getId());
        $query->set('includePhysicalSpec', true);

        if (isset($this['friendlyName'])) {
            $query->set('friendlyName', $this['friendlyName']);
        }

        $packStr = $this['serializer']->serializePack($this['pack']);
        $query->set('pack', $packStr);
    }
}