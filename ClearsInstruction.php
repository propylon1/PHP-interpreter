<?php

namespace IPP\Student;

use IPP\Core;
use IPP\Student\Exception\FrameAccessException;
use IPP\Student\Exception\MissingValueException;

class ClearsInstruction implements InstructionInterface {

    public function __construct() {
    }

    public function execute(): ?int {
        // Access the DataStack singleton instance directly
        $dataStack = DataStack::getInstance();

        // Pop the value from the data stack until exception is thrown
        try {
        $value = $dataStack->pop();
        } catch (MissingValueException $e) {
            return null;
        }
        return null;

    }


}