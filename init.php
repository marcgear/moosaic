<?php
require_once 'vendor/autoload.php';
use Symfony\Component\ClassLoader\UniversalClassLoader;
use Guzzle\Http\Client as GuzzleClient;
use Moo\Client\Client as MooClient;
use Moo\Client\Serializer\DataSerializer;
use Moo\Client\Serializer\PackModelSerializer;
use Moo\Client\Serializer\TypeSerializer;


// classloader
$loader = new UniversalClassLoader();
$loader->registerNamespace('Moosaic', __DIR__.'/lib/');
$loader->registerNamespace('Moo', __DIR__.'/lib/');
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

$serializer = new PackModelSerializer(new DataSerializer(new TypeSerializer()));
$mooClient = MooClient::factory(array(
    'consumer_key'    => 'c3b12ce68314c0ee55bf6928d20eadb104d1d07da',
    'consumer_secret' => '91b4bc021958f6bc77eaccadab7878c3',
    'command.params'  => array('serializer' => $serializer),
));
$mooClient->addSubscriber(\Guzzle\Plugin\Log\LogPlugin::getDebugPlugin());

?><pre>
<?php
$output = $mooClient->createPack();
$spec   = $output->getPhysicalSpec();
$packId = $output->getPack()->getId();

$spec = new \Moo\PackModel\PhysicalSpec(
    $spec->getProductType(),
    'quadplex_red',
    $spec->getFinishingOption(),
    $spec->getPackSize(),
    'nolam'
);

$output = $mooClient->updatePhysicalSpec(
    array(
         'packId'       => $packId, 
         'physicalSpec' => $spec,
    )
);

?></pre><?php
exit;