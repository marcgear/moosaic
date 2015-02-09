<?php
namespace Moosaic;
require_once __DIR__.'/../init.php';

$c = createTinEyeClient();
$options = array(
    'query' => array(
        'photo_id' => 'foobar',
    ),
);
$headers  = array();
$request  = $this->flickrClient->get(null, $headers, $options);
$response = $request->send();
$data     = $response->json();

echo 'DEBUG ON LINE ',__LINE__, ' in ', __FILE__, "\n<pre>\n";
print_r($data);
echo "\n</pre>\n";