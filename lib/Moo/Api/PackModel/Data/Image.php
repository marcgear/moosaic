<?php
namespace Moo\PackModel\Data;

class Image extends Data
{
    protected $imageBox;
    protected $resourceUri;
    protected $imageStoreFileId;
    protected $enhance;

    public function getType()
    {
        return 'imageData';
    }
}