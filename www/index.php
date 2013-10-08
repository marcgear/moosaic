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
$input = DATA_DIR.'/moross.jpg';
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

// this stinks
$serializer = new PackModelSerializer(new DataSerializer(new TypeSerializer()));

$mooClient = MooClient::factory(array(
     'consumer_key'    => 'c3b12ce68314c0ee55bf6928d20eadb104d1d07da',
     'consumer_secret' => '91b4bc021958f6bc77eaccadab7878c3',
     'command.params'  => array('serializer' => $serializer),
));

$img = new InputImage($input);
$img->makeThumb($thumb, 38);
$img->inspect();

//draw($img->getPixels());

$finder = new ImageFinder(createTinEyeClient(), createFlickrClient(), $log, $cache);
$images = array();
foreach ($img->getColours() as $colour => $num) {
    echo '.';
    $images[$colour] = $finder->getImages($colour, $num);
}
$mooClient->addSubscriber(\Guzzle\Plugin\Log\LogPlugin::getDebugPlugin());

echo "<pre>";
$factory = new PackFactory($mooClient, array('product' => 'businesscard'));
$builder = new PackBuilder($factory);
$y = 0;
foreach ($img->getPixels() as $pixels) {
    $x = 0;
    foreach ($pixels as $hex) {
        $colour = new RGB(
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2))
        );
        if (isset($images[$hex])
            && $images[$hex] instanceof \SplStack
            && $images[$hex]->count()) {
            $imageUrl = $images[$hex]->pop();
        }
        $response = $mooClient->importImage(array('imageUrl' => $imageUrl));
        echo 'DEBUG ON LINE ',__LINE__, ' in ', __FILE__, "\n<pre>\n";
        print_r($response);
        echo "\n</pre>\n";
        $builder->addCard($colour, $imageUrl , $x, $y);
        $x++;

    }
    $y++;
}

foreach ($builder->getPacks() as $pack) {
    $response = $mooClient->updatePack(array('pack' => $pack));
    echo 'DEBUG ON LINE ',__LINE__, ' in ', __FILE__, "\n<pre>\n";
    print_r($response->getDropIns());
    echo "\n</pre>\n";
}


echo "</pre>";


// work out how many packs we need
// instantiate the moo pack(s)
// create cards with an image on one side, and position on the other
// add each one to the cart
// link off to it.

function draw($rows, $images = array())
{
?>
    <table border=0>
    <?php
    foreach ($rows as $pixels) {
    ?>
    <tr>
        <?php foreach ($pixels as $pixel) {?>
        <td width="22" height="16" style="background-color:<?php echo $pixel;?>;"><img src="<?php if (isset($images[$pixel][0])) { echo $images[$pixel][0]; }?>"</td>
        <?php } ?>
    </tr>
    <?php } ?>
    </table>
    <?php
}



