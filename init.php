<?php
require_once 'vendor/autoload.php';
use Symfony\Component\ClassLoader\UniversalClassLoader;
use Guzzle\Http\Client as GuzzleClient;
use Moo\Client\Client as MooClient;
// classloader
$loader = new UniversalClassLoader();
$loader->registerNamespace('Moosaic', __DIR__.'/lib/');
$loader->register();

// dumping ground for crap that needs to go elsewhere
function createClient()
{
    $options = array(
        'mode' => 'rest',
        'request.options' => array(
            'query' => array(
                'method'  => 'flickr_color_search',
                'weights' => array(1),
                'offset'  => 0,
            )
        )
    );
    $client = new GuzzleClient('http://labs.tineye.com', $options);
    return $client;
}

$mooClient = MooClient::factory(array(
    'consumer_key'    => '',
    'consumer_secret' => '',
    'token'           => '',
    'token_secret'    => '',
));