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
     * @var \Guzzle\Http\ClientInterface
     */
    protected $flickrClient;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $log;

    /**
     * @var \Doctrine\Common\Cache\Cache
     */
    protected $cache;

    protected $stats = array();

    public function __construct(ClientInterface $tinEyeClient,
                                ClientInterface $flickrClient,
                                LoggerInterface $log,
                                Cache $cache = null)
    {
        $this->tinEyeClient = $tinEyeClient;
        $this->flickrClient = $flickrClient;
        $this->log          = $log;
        $this->cache        = $cache;
        $this->resetStats();
    }

    protected function resetStats()
    {
        $this->stats = array(
            'flickr' => array(
                'fail'    => 0,
                'success' => 0,
            ),
            'tineye' => array(
                'fail'    => 0,
                'success' => 0,
            ),
        );
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
                'limit'   => $num,
                'colors' => array($colour),
            )
        );
        $request = $this->tinEyeClient->get(null, array(), $options);
        $response = $request->send();
        $data = $response->json();

        if (!$response->isSuccessful() || $data['status'] === 'fail') {
            $context = array(
                'data'    => $data,
                'request' => $request->getUrl(),
            );
            $this->log->addNotice('Unexpected TinEye API response', $context);
            $this->stats['tineye']['fail']++;
            return $images;
        }

        foreach ($data['result'] as $result) {
            $url = $this->getFlickrUrl($result['id']);
            $images->push($url);
        }

        if ($this->cache && $images->count()) {
            $this->cacheResult($key, $images);
        }
        ;
        return $images;
    }

    protected function getFlickrUrl($photoId)
    {
        $options = array(
            'query' => array(
                'photo_id' => $photoId,
            ),
        );
        $headers  = array();
        $request  = $this->flickrClient->get(null, $headers, $options);
        $response = $request->send();
        $data     = $response->json();

        if (!$response->isSuccessful() || !isset($data['photo'])) {
            $context = array(
                'data'    => $data,
                'request' => $request->getUrl(),
            );
            $this->log->addNotice('Unexpected Flickr API response', $context);
            $this->stats['flickr']['fail']++;
            return '';
        }
        $this->stats['flickr']['success']++;
        return $this->flickrResponse2Url($data);
    }

    protected function flickrResponse2Url($resp)
    {
        $farmId   = $resp['photo']['farm'];
        $serverId = $resp['photo']['server'];
        $photoId  = $resp['photo']['id'];
        $secret   = $resp['photo']['secret'];
        $size     = 'b';
        $url      = "http://farm{$farmId}.staticflickr.com/{$serverId}/{$photoId}_{$secret}_{$size}.jpg";
        return $url;
    }

    public function getStats()
    {
        return $this->stats;
    }
}