<?php
namespace Moo\Client\Command;

class UpdatePack extends PackCommand
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
        $this->request->addPostFields(array('pack' => $packStr));
    }

    public function process()
    {
        $this->result = PackMethodOutput::fromCommand($this);
    }
}