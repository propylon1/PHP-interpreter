<?php

namespace IPP\Student\Exception;

use IPP\Core\Exception\IPPException;
use IPP\Core\ReturnCode;
use Throwable;

class VariableAccessException extends IPPException {
    public function __construct(string $message = "Accessing a non-existent variable.", ?Throwable $previous = null) {
        parent::__construct($message, ReturnCode::VARIABLE_ACCESS_ERROR, $previous);
    }
}