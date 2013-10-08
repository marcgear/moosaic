<?php
namespace Moo\Client;

use Guzzle\Common\Collection;
use Guzzle\Plugin\Oauth\OauthPlugin;
use Guzzle\Service\Client as GuzzleClient;
use Guzzle\Service\Description\ServiceDescription;

class Client extends GuzzleClient
{
    public static function factory($config = array())
    {
        $default = array(
            'base_url' => 'http://www.moo.com/api/service',
        );
        $required = array(
            'base_url',
            'consumer_key',
            'consumer_secret',
        );

        // merge in the defaults and validate the config
        $config = Collection::fromConfig($config, $default, $required);

        // instantiate one of us
        $client = new self($config->get('base_url'), $config);

        // Ensure OauthPlugin is always attached
        $client->addSubscriber(new OauthPlugin($config->toArray()));

        // Set the service description
        $client->setDescription(ServiceDescription::factory(__DIR__.'/moo.json'));

        return $client;
    }

    public static function parsePack($data)
    {
        return $data;
    }
}