<?php
require_once 'vendor/autoload.php';
use Symfony\Component\ClassLoader\UniversalClassLoader;
use Guzzle\Http\Client;

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
    $client = new Client('http://labs.tineye.com', $options);
    return $client;
}
