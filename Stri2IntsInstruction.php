<?php

namespace IPP\Student;

use IPP\Student\FrameManager;
use IPP\Student\Exception\StringException;
use IPP\Student\Exception\OperandTypeException;

class Stri2IntsInstruction implements InstructionInterface {
    private $target;
    private FrameManager $frameManager;

    public function __construct($target, FrameManager $frameManager) {
        $this->target = $target;
        $this->frameManager = $frameManager;
    }

    public function execute(): ?int {
        // Retrieve the string and index from source
        $indexValue = DataStack::getInstance()->pop();
        $stringValue = DataStack::getInstance()->pop();
        

        if (!is_string($stringValue)) {
            throw new OperandTypeException;
        }

        if (!is_numeric($indexValue) || $indexValue < 0 || $indexValue >= mb_strlen($stringValue)) {
            throw new StringException;
        }

        // Convert the character to its Unicode value
        $char = mb_substr($stringValue, $indexValue, 1);
        $unicodeValue = mb_ord($char);

        // Store the Unicode value into the target variable
        $this->frameManager->storeResult($this->target, $unicodeValue);
        return null;
    }
}