<?php

namespace IPP\Student;

use IPP\Student\FrameManager;
use IPP\Student\Exception\StringException;
use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\FrameAccessException;

class GetcharInstruction implements InstructionInterface {
    private $resultVar;
    private $string1;
    private $index;
    private FrameManager $frameManager;

    public function __construct($resultVar, $string1, $index, FrameManager $frameManager) {
        $this->resultVar = $resultVar;
        $this->string1 = $string1;
        $this->index = $index;
        $this->frameManager = $frameManager;
    }

    public function execute(): ?int {
        // Resolve values for operands, ensure they are strings
        $stringValue = $this->frameManager->getSourceValue($this->string1);
        $indexValue = $this->frameManager->getSourceValue($this->index);
        
        if (!is_numeric($indexValue) || $indexValue < 0 || $indexValue >= mb_strlen($stringValue)) {
            throw new StringException;
        }

        $char = mb_substr($stringValue, $indexValue, 1);

        // Store the result in the specified variable
        $this->frameManager->storeResult($this->resultVar, $char);
        return null;
    }

}