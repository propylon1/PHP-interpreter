<?php

namespace IPP\Student;

use IPP\Student\Exception\FrameAccessException;
use IPP\Student\Exception\OperandTypeException;

class WriteInstruction implements InstructionInterface {
    private $source;
    private FrameManager $frameManager;

    public function __construct($source, FrameManager $frameManager) {
        $this->source = $source;
        $this->frameManager = $frameManager;
    }

    public function execute(): ?int {
        // Resolve the source value
        $value = $this->frameManager->getSourceValue($this->source);
        if ($value == 'nil' || $value == 'nil@nil') {
            $value = "";
        }
        // Perform the write operation
        echo $value . "";
        return null;
    }


}
