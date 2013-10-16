<?php
namespace Moo\Client\Command;

use Moo\Client\Output\PackMethodOutput;

/**
 * Class CreatePack
 * Command to create a pack over the API
 *
 * @package Moo\Client\Command
 */
class CreatePack extends PackCommand
{
    /**
     * Build the command
     * Will _always_ setIncludePhysicalSpec
     */
    protected function build()
    {
        // setup the request
        $this->request = $this->client->post();
        $query = $this->request->getQuery();
        $query->set('method', 'moo.pack.createPack');
        $query->set('includePhysicalSpec', 'true');

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
            $query->set('product', 'businesscard');
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