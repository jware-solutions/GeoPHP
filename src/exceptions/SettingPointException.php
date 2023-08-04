<?php

namespace JWare\GeoPHP\Exceptions;

public $message;

class SettingPointException extends \Throwable {
    public function __construct($message, $code = 0, Exception $previous = null) {
        $this->message = $message;
        $this->__construct($message, $code, $previous);
    }
}

?>
