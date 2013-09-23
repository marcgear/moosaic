<?php
namespace Moo\Client\Command;

use Guzzle\Service\Command\AbstractCommand;
use Moo\Client\Serializer\PackModelSerializer;

class UpdatePhysicalSpec extends AbstractCommand
{
    protected function build()
    {
        // serialize the physical spec
        $specStr = $this['serializer']->serializePhysicalSpec($this['physicalSpec']);

        // setup the request
        $this->request = $this->client->post();
        $query = $this->request->getQuery();
        $query->set('method', 'moo.pack.updatePhysicalSpec');
        $query->set('packId', $this['packId']);
        $query->set('physicalSpec', $specStr);
    }
}