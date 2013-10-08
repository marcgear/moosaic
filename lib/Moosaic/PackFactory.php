<?php
namespace Moosaic;

use Moo\Client\Client;

class PackFactory
{
    protected $client;
    protected $options;

    public function __construct(Client $client, $options)
    {
        $this->client  = $client;
        $this->options = $options;
    }

    /**
     * @param array $options
     *
     * @return \Moo\PackModel\Pack
     */
    public function createPack($options = array())
    {
        if (!$options) {
            $options = $this->options;
        }
        echo 'DEBUG ON LINE ',__LINE__, ' in ', __FILE__, "\n<pre>\n";
        print_r($options);
        echo "\n</pre>\n";
        exit;
        $output = $this->client->createPack($options);
        return $output->getPack();
    }
}