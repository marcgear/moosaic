<?php

namespace Moosaic;
require_once __DIR__.'/../init.php';

$c = createTinEyeClient();
$options = array(
    'query' => array(
        'limit'   => 10,
        'colors' => array('697397'),
    )
);
$request = $c->get('color_search', array(), $options);
$response = $request->send();
$data = $response->json();


foreach ($data['result'] as $result) {
    $url = filePath2Url($result['filepath']);

    echo 'DEBUG ON LINE ',__LINE__, ' in ', __FILE__, "\n<pre>\n";
    echo "<img src='$url'><br/><br/>";
    echo "\n</pre>\n";
}

function filePath2Url($fp)
{
    global $c;

    $options = array(
        'query' => array(
            'filepaths' => array($fp),
        ),
    );

    $req  = $c->get('get_metadata', array(), $options);
    $resp = $req->send();
    $out  = $resp->json();

    $farmId   = $out['result'][0]['metadata']['farmID'][""];
    $serverId = $out['result'][0]['metadata']['serverID'][""];
    $photoId  = $out['result'][0]['metadata']['photoID'][""];
    $secret   = $out['result'][0]['metadata']['photoSecret'][""];

    $url = sprintf('http://farm%s.staticflickr.com/%s/%s_%s_z.jpg',
        $farmId,
        $serverId,
        $photoId,
        $secret);
    return $url;
}

