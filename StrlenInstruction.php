<?php

namespace IPP\Student;

use IPP\Student\FrameManager;
use IPP\Student\Exception\StringException;
use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\FrameAccessException;

class StrlenInstruction implements InstructionInterface {
    private $resultVar;
    private $string1;
    private FrameManager $frameManager;

    public function __construct($resultVar, $string1, FrameManager $frameManager) {
        $this->resultVar = $resultVar;
        $this->string1 = $string1;
        $this->frameManager = $frameManager;
    }

    public function execute(): ?int {

        // Resolve values for operands, ensure they are strings
        $val1 = $this->frameManager->getSourceValue($this->string1);


        // Store the result in the specified variable
        $this->frameManager->storeResult($this->resultVar, mb_strlen($val1));
        return null;
    }

}
