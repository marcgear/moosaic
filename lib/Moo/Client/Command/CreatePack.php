<?php
namespace Moo\Client\Command;

use Guzzle\Service\Command\AbstractCommand;

class CreatePack extends AbstractCommand
{
    protected function build()
    {
        // setup the request
        $this->request = $this->client->post();
        $query = $this->request->getQuery();
        $query->set('method', 'moo.pack.createPack');
        $query->set('packId', $this['packId']);
        $query->set('includePhysicalSpec', true);

        if (isset($this['trackingId'])) {
            $query->set('trackingId', $this['trackingId']);
        }

        if (isset($this['friendlyName'])) {
            $query->set('friendlyName', $this['friendlyName']);
        }

        if (isset($this['startAgainUrl'])) {
            $query->set('startAgainUrl', $this['startAgainUrl']);
        }

        if (isset($this['product'])) {
            $query->set('product', $this['product']);
        } else {
            $query->set('product', 'businesscards');
        }

        // serialize the pack and physical spec if they've been given
        if (isset($this['physicalSpec'])) {
            $specStr = $this['serializer']->serializePhysicalSpec($this['physicalSpec']);
            $query->set('physicalSpec', $specStr);
        }
        if (isset($this['pack'])) {
            $packStr = $this['serializer']->serializePack($this['pack']);
            $query->set('pack', $packStr);
        }
    }
}