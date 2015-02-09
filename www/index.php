<?php
namespace Moosaic;
require_once __DIR__.'/../init.php';
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Doctrine\Common\Cache\MemcachedCache;
use Moo\Client\Client as MooClient;
use Moo\Client\Serializer\DataSerializer;
use Moo\Client\Serializer\PackModelSerializer;
use Moo\Client\Serializer\TypeSerializer;
use Moo\PackModel\Type\RGB;

define('DATA_DIR', __DIR__ . '/../data');
$input = DATA_DIR.'/richard3.png';
//$input = DATA_DIR.'/john.png';
//$input = DATA_DIR.'/dave3.jpg';
//$input = DATA_DIR .'/yay.jpg';

$thumb = DATA_DIR.'/tmp/thumb.jpg';
if (file_exists($thumb)) {
    unlink($thumb);
}

$log = new Logger('moosaic');
$log->pushHandler(new StreamHandler(DATA_DIR.'/log/moosaic.log', Logger::DEBUG));

$memcached = new \Memcached();
$memcached->addServer('localhost', 11211);

$cache = new \Doctrine\Common\Cache\MemcachedCache();
$cache->setMemcached($memcached);

if (isset($_GET['flushCache'])) {
    $cache->flushAll();
};

// this stinks
$serializer = new PackModelSerializer(new DataSerializer(new TypeSerializer()));

$mooClient = MooClient::factory(array(
     'consumer_key'    => 'c3b12ce68314c0ee55bf6928d20eadb104d1d07da',
     'consumer_secret' => '91b4bc021958f6bc77eaccadab7878c3',
     'command.params'  => array('serializer' => $serializer),
));
//$mooClient->addSubscriber(\Guzzle\Plugin\Log\LogPlugin::getDebugPlugin());

// read the image
$img = new InputImage($input, 'bc-l');
$img->makeThumb($thumb, 2000);
$img->inspect();
$img->draw();


$finder = new ImageFinder(createTinEyeClient(), $log, $cache);
$images = array();
$colours = $img->getColours();

foreach ($colours as $colour => $num) {
    flush();
    //ob_flush();
    $images[$colour] = $finder->getImages($colour, $num);
}
$cols = $img->getColours();


$factory = new PackFactory($mooClient, array('product' => 'businesscard_square'));
$builder = new PackBuilder($factory);
$y = 0;
$missing = 0;
foreach ($img->getPixels() as $pixels) {
    $x = 0;
    $used = array();
    foreach ($pixels as $hex) {
        $colour = hex2rgb($hex);

        if (isset($images[$hex])
            && $images[$hex] instanceof \SplStack
            && $images[$hex]->count()) {

            do {
                $imageUrl = $images[$hex]->pop();
            } while (!isset($used[$imageUrl]));

            // skip over any redirects
            do {
                $resp = http_head($imageUrl);
                $notOK = !preg_match('/HTTP\/\d\.\d 2\d\d/', $resp);
                if ($notOK) {
                    $imageUrl = $images[$hex]->pop();
                }
            } while ($notOK);
            }

        if (isset($imageUrl)) {
            if ($cache->contains($imageUrl)) {
                $output = $cache->fetch($imageUrl);
            } else {
                $output = $mooClient->importImage(array('imageUrl' => $imageUrl));
            }

            $used[$imageUrl] = true;

            $item = $output->getImageBasketItem();
            $cache->save($imageUrl, $output);
        } else {
            $item = null;
            $missing++;
        }
        $builder->addCard($colour, $item , $x, $y);
        $x++;
    }
    $y++;
}

function hex2rgb($hex)
{
    return new RGB(
        hexdec(substr($hex, 0, 2)),
        hexdec(substr($hex, 2, 2)),
        hexdec(substr($hex, 4, 2))
    );
}

//echo 'missing: ', $missing, "\n";

foreach ($builder->getPacks() as $pack) {
    $response = $mooClient->updatePack(
        array(
            'pack' => $pack,
        )
    );
    echo 'DEBUG ON LINE ',__LINE__, ' in ', __FILE__, "\n<pre>\n";
    print_r($response->getDropIns());
    echo "\n</pre>\n";
}


echo "</pre>";
