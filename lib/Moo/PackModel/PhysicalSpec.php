<?php
namespace Moo\PackModel;


class PhysicalSpec
{
    protected $productType;
    protected $paperClass;
    protected $finishingOption;
    protected $packSize;
    protected $paperLaminate;

    public function __construct($productType, $paperClass, $finishingOption, $packSize, $paperLaminate)
    {
        $this->productType     = $productType;
        $this->paperClass      = $paperClass;
        $this->finishingOption = $finishingOption;
        $this->packSize        = $packSize;
        $this->paperLaminate   = $paperLaminate;
    }

    public function getProductType()
    {
        return $this->productType;
    }

    public function getPaperClass()
    {
        return $this->paperClass;
    }

    public function getFinishingOption()
    {
        return $this->finishingOption;
    }

    public function getPackSize()
    {
        return $this->packSize;
    }

    public function getPaperLaminate()
    {
        return $this->paperLaminate;
    }

}