<?php
namespace Moo\Client;

use Guzzle\Common\Collection;
use Guzzle\Plugin\Oauth\OauthPlugin;
use Guzzle\Service\Client as GuzzleClient;
use Guzzle\Service\Description\ServiceDescription;
use Moo\Client\Serializer\PackModelSerializer;

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
            'serializer',
        );

        // merge in the defaults and validate the config
        $config = Collection::fromConfig($config, $default, $required);

        if ($config['serializer'] instanceof PackModelSerializer) {
            throw new InvalidArgumentException('Config is expecting key \'serializer\' to be of type
            \Moo\Client\Serializer\PackModelSerializer');
        }

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