<?php
namespace Moo\PackModel\Data;

abstract class Data
{
    protected $linkId;

    abstract function getType();

    public function getLinkId()
    {
        return $this->linkId;
    }

    public function setLinkId($id)
    {
        $this->linkId = $id;
    }
}