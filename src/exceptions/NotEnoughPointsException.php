<?php

namespace JWare\GeoPHP\Exceptions;

class NotEnoughPointsException extends \Exception {
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

?>