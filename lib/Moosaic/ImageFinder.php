<?php
namespace Moosaic;
use Doctrine\Common\Cache\Cache;
use Guzzle\Http\ClientInterface;
use Psr\Log\LoggerInterface;

class ImageFinder
{
    /**
     * @var \Guzzle\Http\ClientInterface
     */
    protected $tinEyeClient;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $log;

    /**
     * @var \Doctrine\Common\Cache\Cache
     */
    protected $cache;

    public function __construct(ClientInterface $tinEyeClient,
                                LoggerInterface $log,
                                Cache $cache)
    {
        $this->tinEyeClient = $tinEyeClient;
        $this->log          = $log;
        $this->cache        = $cache;
    }

    protected function getCacheResult($key)
    {
        $images = new \SplStack();
        foreach ($this->cache->fetch($key) as $image) {
            $images->push($image);
        }
        return $images;
    }

    protected function cacheResult($key, \SplStack $images)
    {
        $array = array();
        foreach ($images as $image) {
            $array[] = $image;
        }
        $this->cache->save($key, $array);
    }


    public function getImages($colour, $num)
    {
        $key = implode('-', array('images', $colour, $num));
        if ($this->cache && $this->cache->contains($key)) {
            return $this->getCacheResult($key);
        }

        $images = new \SplStack();

        $options = array(
            'query' => array(
                'limit'   => $num + 15,
                'colors' => array($colour),
            )
        );
        $request = $this->tinEyeClient->get('color_search', array(), $options);
        $response = $request->send();
        $data = $response->json();

        if (!$response->isSuccessful() || $data['status'] === 'fail') {
            $context = array(
                'data'    => $data,
                'request' => $request->getUrl(),
            );
            $this->log->addNotice('Unexpected TinEye API response', $context);
            echo '~';
            return $images;
        }
        foreach ($data['result'] as $row) {
            $url = $this->getFlickrUrl($row['filepath']);
            echo '.';
            if ($url) {
                $images->push($url);
            }
        }

        if ($this->cache && $images->count()) {
            $this->cacheResult($key, $images);
        }

        return $images;
    }

    protected function getFlickrUrl($fp)
    {
        $options = array(
            'query' => array(
                'filepaths' => array($fp),
            ),
        );

        $req  = $this->tinEyeClient->get('get_metadata', array(), $options);
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
}