<?php
namespace Moo\PackModel;

class Side
{
    protected $type;
    protected $sideNum;
    protected $templateCode;

    /**
     * @var \SplObjectStorage
     */
    protected $data;

    public function __construct($type, $sideNum, $templateCode)
    {
        $this->type         = $type;
        $this->sideNum      = $sideNum;
        $this->templateCode = $templateCode;
        $this->data         = new \SplObjectStorage();
    }

    public function getType()
    {
        return $this->type;
    }

    public function getSideNum()
    {
        return $this->sideNum;
    }

    public function getTemplateCode()
    {
        return $this->templateCode;
    }

    public function getData()
    {
        $data = array();
        foreach ($this->data as $item) {
            $data[] = $item;
        }
        return $data;
    }

    public function addData(Data $data)
    {
        $this->data->attach($data);
    }

    public function hasData(Data $data)
    {
        return $this->data->contains($data);
    }

    public function removeData(Data $data)
    {
        $this->data->detach($data);
    }
}