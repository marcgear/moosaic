<?php
namespace Moo\PackModel\Data;

use Moo\PackModel\Type\Box as BoundingBox;

class Image extends Data
{
    /**
     * @var BoundingBox
     */
    protected $imageBox;

    /**
     * @var String
     */
    protected $resourceUri;

    /**
     * @var String
     */
    protected $imageStoreFileId;

    /**
     * @var Boolean
     */
    protected $enhance;

    const TYPE = 'imageData';

    public function __construct($linkId, BoundingBox $box, $resourceUri, $imageStoreFileId, $enhance)
    {
        parent::__construct($linkId);
        $this->box              = $box;
        $this->resourceUri      = $resourceUri;
        $this->imageStoreFileId = $imageStoreFileId;
        $this->enhance          = $enhance;
    }

    public function getType()
    {
        return self::TYPE;
    }

    public function getImageBox()
    {
        return $this->imageBox;
    }

    public function getResourceUri()
    {
        return $this->resourceUri;
    }

    public function getImageStoreFileId()
    {
        return $this->imageStoreFileId;
    }

    public function getEnhance()
    {
        return $this->enhance;
    }

}