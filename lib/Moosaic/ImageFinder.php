<?php
namespace Moosaic;
use Doctrine\Common\Cache\Cache;
use Guzzle\Http\ClientInterface;
use Psr\Log\LoggerInterface;

class ImageFinder
{
    /**
     * @var \Guzzle\Http\Client
     */
    protected $_client;

    /**
     * @var string
     */
    protected $_path;

    /**
     * @var \Monolog\Logger
     */
    protected $_log;

    /**
     * @var \Doctrine\Common\Cache\Cache
     */
    protected $_cache;

    public function __construct(ClientInterface $client, $path, LoggerInterface $log, Cache $cache)
    {
        $this->_client = $client;
        $this->_path   = $path;
        $this->_log    = $log;
        $this->_cache  = $cache;
    }

    public function getImages($colour, $num)
    {
        $key = implode('-', array('images', $colour, $num));
        if ($this->_cache->contains($key)) {
            return $this->_cache->fetch($key);
        }
        $images = array();
        $options = array(
            'query' => array(
                'limit'   => $num,
                'colors' => array($colour),
            )
        );
        $request = $this->_client->get($this->_path, array(), $options);
        $response = $request->send();
        $data = $response->json();

        if (!$response->isSuccessful() || $data['status'] === 'fail') {
            $context = array(
                'data'    => $data,
                'request' => $request->getUrl(),
            );
            $this->_log->addNotice('Unexpected API response', $context);
            return array();
        }

        foreach ($data['result'] as $result) {
            $images[] = $result['id'];
        }
        if ($images) {
            $this->_cache->save($key, $images);
        }
        return $images;
    }
}