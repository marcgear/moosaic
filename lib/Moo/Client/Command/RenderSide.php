<?php
namespace Moo\Client\Command;

use Guzzle\Service\Command\AbstractCommand;

class RenderSide extends AbstractCommand
{
    protected function build()
    {
        $this->request = $this->client->post();
        $query = $this->request->getQuery();
        $query->set('method', 'moo.pack.renderSide');

        if (isset($this['boxType'])) {
            $query->set('boxType', $this['boxType']);
        }

        if (isset($this['maxSide'])) {
            $query->set('maxSide', $this['maxSide']);
        }

        $imageBasketStr = $this['serializer']->serializeImageBasket($this['imageBasket']);
        $query->set('imageBasket', $imageBasketStr);

        $sideStr = $this['serializer']->serializeSide($this['side']);
        $query->set('side', $sideStr);
    }
}