<?php
namespace Moo\PackModel\Data;

class MultiLineText extends Text
{
    const TYPE = 'multiLineTextData';

    public function getType()
    {
        return self::TYPE;
    }
}