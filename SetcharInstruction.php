<?php

namespace IPP\Student;

use IPP\Student\FrameManager;
use IPP\Student\Exception\StringException;
use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\FrameAccessException;

class SetcharInstruction implements InstructionInterface {
    private $resultVar;
    private $string1;
    private $index;
    private FrameManager $frameManager;

    public function __construct($resultVar, $index, $string1, FrameManager $frameManager) {
        $this->resultVar = $resultVar;
        $this->index = $index;
        $this->string1 = $string1;
        $this->frameManager = $frameManager;
    }

    public function execute(): ?int {
        // Resolve values for operands, ensure they are strings
        $varValue = $this->frameManager->getSourceValue($this->resultVar);
        $indexValue = $this->frameManager->getSourceValue($this->index);
        $strValue = $this->frameManager->getSourceValue($this->string1);


        if (!is_numeric($indexValue) || $indexValue < 0 || $indexValue >= mb_strlen($varValue)) {
            throw new StringException;
        }
        $firstChar = mb_substr($strValue, 0, 1);
        $result = substr_replace($varValue, $firstChar, intval($indexValue), 1);

        // Store the result in the specified variable
        $this->frameManager->storeResult($this->resultVar['value'], $result);
        return null;
    }

}