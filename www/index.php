<?php
namespace Moosaic;
require_once __DIR__.'/../init.php';
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Doctrine\Common\Cache\MemcachedCache;

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


$img = new Image($input);
$img->makeThumb($thumb);
$img->inspect();

draw($img->getPixels());

$finder = new ImageFinder(createClient(), '/rest/', $log, $cache);
$images = array();
foreach ($img->getColours() as $colour => $num) {
    $images[$colour] = $finder->getImages($colour, $num);
}


// work out how many packs we need
// instantiate the moo pack(s)
// create cards with an image on one side, and position on the other
// add each one to the cart
// link off to it.

//draw($img->getPixels(), $images);

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



