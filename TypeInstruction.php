<?php

namespace IPP\Student;

use IPP\Student\FrameManager;
use IPP\Student\Exception\StringException;
use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\FrameAccessException;

class TypeInstruction implements InstructionInterface {
    private $target;
    private $symb;
    private FrameManager $frameManager;

    public function __construct($target, $symb, FrameManager $frameManager) {
        $this->target = $target;
        $this->symb = $symb;
        $this->frameManager = $frameManager;
    }

    public function execute(): ?int {
        $symbVal = $this->frameManager->getSourceValue($this->symb);
        if (empty($symbVal)) {
            $this->frameManager->storeResult($this->target, 'nil');
            return null;
        }
        $this->frameManager->storeResult($this->target, $this->frameManager->determineType($symbVal));
        return null;

}

}