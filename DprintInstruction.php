<?php

namespace IPP\Student;

use IPP\Student\FrameManager;
use IPP\Student\Exception\StringException;
use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\FrameAccessException;

class DprintInstruction implements InstructionInterface {
    private $symb;
    private FrameManager $frameManager;

    public function __construct($symb, FrameManager $frameManager) {
        $this->symb = $symb;
        $this->frameManager = $frameManager;
    }

    public function execute(): ?int {
        fwrite(STDERR, $this->frameManager->getSourceValue($this->symb));

        return null;
    }

}