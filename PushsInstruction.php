<?php

namespace IPP\Student;

use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\FrameAccessException;
use IPP\Student\Exception\InvalidOperandValueException;

class PushsInstruction implements InstructionInterface {
    private $source;
    private FrameManager $frameManager;


    public function __construct($source, FrameManager $frameManager) {
        $this->source = $source;
        $this->frameManager = $frameManager;
    }


    public function execute(): ?int {
        // Push the value onto the data stack
        $value = $this->frameManager->getSourceValue($this->source);
        DataStack::getInstance()->push($value);
        return null;

    }


}
