<?php

namespace IPP\Student;

use IPP\Student\FrameManager;
use IPP\Student\Exception\StringException;
use IPP\Student\Exception\OperandTypeException;

class Stri2IntInstruction implements InstructionInterface {
    private $target;
    private $source;
    private $index;
    private FrameManager $frameManager;

    public function __construct($target, $source, $index, FrameManager $frameManager) {
        $this->target = $target;
        $this->source = $source;
        $this->index = $index;
        $this->frameManager = $frameManager;
    }

    public function execute(): ?int {
        // Retrieve the string and index from source
        $stringValue = $this->frameManager->getSourceValue($this->source);
        $indexValue = $this->frameManager->getSourceValue($this->index);

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
