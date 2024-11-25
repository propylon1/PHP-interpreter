<?php

namespace IPP\Student\Exception;

use IPP\Core\Exception\IPPException;
use IPP\Core\ReturnCode;
use Throwable;

class InvalidSourceStructureException extends IPPException {
    public function __construct(string $message = "Invalid source code structure.", ?Throwable $previous = null) {
        parent::__construct($message, ReturnCode::INVALID_SOURCE_STRUCTURE, $previous);
    }
}
