<?php
namespace Moo\PackModel\Data;

abstract class Data
{
    protected $linkId;

    abstract function getType();

    public function __construct($linkId)
    {
        $this->linkId = $linkId;
    }

    public function getLinkId()
    {
        return $this->linkId;
    }

    public function setLinkId($id)
    {
        $this->linkId = $id;
    }
}