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
    protected $_tinEyeClient;

    /**
     * @var \Guzzle\Http\ClientInterface
     */
    protected $_flickrClient;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_log;

    /**
     * @var \Doctrine\Common\Cache\Cache
     */
    protected $_cache;

    public function __construct(ClientInterface $tinEyeClient,
                                ClientInterface $flickrClient,
                                LoggerInterface $log,
                                Cache $cache = null)
    {
        $this->_tinEyeClient = $tinEyeClient;
        $this->_flickrClient = $flickrClient;
        $this->_log          = $log;
        $this->_cache        = $cache;
    }

    protected function getCacheResult($key)
    {
        $images = new \SplStack();
        foreach ($this->_cache->fetch($key) as $image) {
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
        $this->_cache->save($key, $array);
    }

    public function getImages($colour, $num)
    {
        $key = implode('-', array('images', $colour, $num));
        if ($this->_cache && $this->_cache->contains($key)) {
            return $this->getCacheResult($key);
        }
        $images = new \SplStack();
        $options = array(
            'query' => array(
                'limit'   => $num,
                'colors' => array($colour),
            )
        );
        $request = $this->_tinEyeClient->get(null, array(), $options);
        $response = $request->send();
        $data = $response->json();

        if (!$response->isSuccessful() || $data['status'] === 'fail') {
            $context = array(
                'data'    => $data,
                'request' => $request->getUrl(),
            );
            $this->_log->addNotice('Unexpected TinEye API response', $context);
            return $images;
        }

        foreach ($data['result'] as $result) {
            $url = $this->getFlickrUrl($result['id']);
            $images->push($url);
        }

        if ($this->_cache && $images->count()) {
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
        $request  = $this->_flickrClient->get(null, $headers, $options);
        $response = $request->send();
        $data     = $response->json();

        if (!$response->isSuccessful() || !isset($data['photo'])) {
            $context = array(
                'data'    => $data,
                'request' => $request->getUrl(),
            );
            $this->_log->addNotice('Unexpected Flickr API response', $context);
            return '';
        }
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
}