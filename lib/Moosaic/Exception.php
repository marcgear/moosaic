<?php
namespace Moosaic;
class Exception extends \Exception {

    public $extra = array();

    public function __construct($message = '',
                                $code = 0,
                                Exception $previous = null,
                                $extra = array())
    {
        parent::__construct($message, $code, $previous);
        $this->extra = $extra;
    }
}