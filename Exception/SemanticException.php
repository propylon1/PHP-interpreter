<?php

namespace IPP\Student\Exception;

use IPP\Core\Exception\IPPException;
use IPP\Core\ReturnCode;
use Throwable;

class SemanticException extends IPPException {
    public function __construct(string $message = "Semantic error in source code.", ?Throwable $previous = null) {
        parent::__construct($message, ReturnCode::SEMANTIC_ERROR, $previous);
    }
}