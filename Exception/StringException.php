<?php

namespace IPP\Student\Exception;

use IPP\Core\Exception\IPPException;
use IPP\Core\ReturnCode;
use Throwable;

class StringException extends IPPException {
    public function __construct(string $message = "String operation error.", ?Throwable $previous = null) {
        parent::__construct($message, ReturnCode::STRING_OPERATION_ERROR, $previous);
    }
}