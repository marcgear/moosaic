<?php
require_once 'vendor/autoload.php';
use Symfony\Component\ClassLoader\UniversalClassLoader;
use Guzzle\Http\Client as Client;

// classloader
$loader = new UniversalClassLoader();
$loader->registerNamespace('Moosaic', __DIR__.'/lib/');
$loader->registerNamespace('Moo', __DIR__.'/lib/');
$loader->register();

// dumping ground for crap that needs to go elsewhere
function createTinEyeClient()
{
    $options = array(
        'base_url' => 'http://labs.tineye.com/multicolr/rest',
        'mode' => 'rest',
        'request.options' => array(
            'query' => array(
                'weights' => array(1),
                'offset'  => 0,
            ),
        ),
    );
    return new Client($options['base_url'], $options);
}
