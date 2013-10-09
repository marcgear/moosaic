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
        'base_url' => 'http://labs.tineye.com/rest/',
        'mode' => 'rest',
        'request.options' => array(
            'query' => array(
                'method'  => 'flickr_color_search',
                'weights' => array(1),
                'offset'  => 0,
            ),
        ),
    );
    return new Client($options['base_url'], $options);
}

function createFlickrClient()
{

    $key     = 'a94022e5f47bee8145b2327b878d8cbb';
    $secret  = '664bbd992484aca5';
    $options = array(
        'base_url' => 'http://api.flickr.com/services/rest/',
        'mode'     => 'rest',
        'request.options' => array (
            'query' => array(
                'method'  => 'flickr.photos.getInfo',
                'format'  => 'json',
                'api_key' => $key,
                'secret'  => $secret,
                'nojsoncallback' => 1,
            ),
        ),
    );
    return new Client($options['base_url'], $options);
}
