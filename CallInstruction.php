<?php

namespace IPP\Student;

use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\FrameAccessException;
use IPP\Student\Exception\InvalidOperandValueException;

class CallInstruction implements InstructionInterface {
    private $source;
    private LabelManager $labelManager;


    public function __construct($source, LabelManager $labelManager) {
        $this->source = $source;
        $this->labelManager = $labelManager;
    }


    public function execute(): ?int {
        return $this->labelManager->getLabelIndex($this->source);
    }

    

}
