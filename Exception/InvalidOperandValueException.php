<?php

namespace IPP\Student\Exception;

use IPP\Core\Exception\IPPException;
use IPP\Core\ReturnCode;
use Throwable;

class InvalidOperandValueException extends IPPException {
    public function __construct(string $message = "Operand value error.", ?Throwable $previous = null) {
        parent::__construct($message, ReturnCode::OPERAND_VALUE_ERROR, $previous);
    }
}