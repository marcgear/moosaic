<?php
require_once __DIR__.'/../init.php';
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use Moo\Client\Client as MooClient;
use Moo\Client\Serializer\DataSerializer;
use Moo\Client\Serializer\PackModelSerializer;
use Moo\Client\Serializer\TypeSerializer;

use Moo\PackModel\Pack;
use Moo\PackModel\Side;
use Moo\PackModel\Data\Box;
use Moo\PackModel\Data\Image;
use Moo\PackModel\Type\Point;
use Moo\PackModel\Type\Box as BoundingBox;

define('DATA_DIR', __DIR__ . '/../data');

// Non-
$frontImageUrl = 'https://pdf.yt/d/hkHSkb9XeynyfTs6/download';
$backImageUrl = 'https://pdf.yt/d/Pd1AeUcMjgtaxNkQ/download';
$middleImageUrl = 'https://pdf.yt/d/yPDNwrWgNU8oHrfR/download';


$log = new Logger('hc');
$log->pushHandler(new StreamHandler(DATA_DIR.'/log/moosaic.log', Logger::DEBUG));

$serializer = new PackModelSerializer(new DataSerializer(new TypeSerializer()));

$mooClient = MooClient::factory(array(
    'consumer_key'    => 'c3b12ce68314c0ee55bf6928d20eadb104d1d07da',
    'consumer_secret' => '91b4bc021958f6bc77eaccadab7878c3',
    'command.params'  => array('serializer' => $serializer),
));

$mooClient->addSubscriber(\Guzzle\Plugin\Log\LogPlugin::getDebugPlugin());
$output = $mooClient->createPack(array('product' => 'holidaycard'));
$pack   = $output->getPack();

// set envelope colour
$envelopeExtra = new \Moo\PackModel\Extra('envelope_colour', 'white');
$pack->addExtra($envelopeExtra);

// front image
$frontOutput  = $mooClient->importImage(array('imageUrl' => $frontImageUrl));
$middleOutput = $mooClient->importImage(array('imageUrl' => $middleImageUrl));
$backOutput   = $mooClient->importImage(array('imageUrl' => $backImageUrl));

// front
$imageBasketItem = $frontOutput->getImageBasketItem();
$side    = new Side(Side::TYPE_IMAGE, 1, 'holidaycard_full_image_portrait');
$pack->getImageBasket()->addItem($imageBasketItem);
$box = new BoundingBox(new Point(53.5, 76), 107, 152, 0);
$image = new Image(
    'variable_image_front',
    $box,
    $imageBasketItem->getResourceUri(),
    null,
    false
);
$side->addData($image);
$pack->addSide($side);

// middle
$imageBasketItem = $middleOutput->getImageBasketItem();
$side    = new Side(Side::TYPE_MIDDLE, 1, 'holidaycard_middle_full_image_portrait');
$pack->getImageBasket()->addItem($imageBasketItem);
$box = new BoundingBox(new Point(53.5, 76), 107, 152, 0);
$image = new Image(
    'variable_image_middle',
    $box,
    $imageBasketItem->getResourceUri(),
    null,
    false
);
$side->addData($image);
$pack->addSide($side);

// front
$imageBasketItem = $backOutput->getImageBasketItem();
$side    = new Side(Side::TYPE_DETAILS, 1, 'holidaycard_back_full_image_portrait');
$pack->getImageBasket()->addItem($imageBasketItem);
$box = new BoundingBox(new Point(53.5, 76), 107, 152, 0);
$image = new Image(
    'variable_image_back',
    $box,
    $imageBasketItem->getResourceUri(),
    null,
    false
);
$side->addData($image);
$pack->addSide($side);

$response = $mooClient->updatePack(
    array(
        'pack' => $pack,
    )
);

echo 'DEBUG ON LINE ',__LINE__, ' in ', __FILE__, "\n<pre>\n";
print_r($response);
echo "\n</pre>\n";
