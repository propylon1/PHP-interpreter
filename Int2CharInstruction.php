<?php

namespace IPP\Student;

use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\StringException;

class Int2CharInstruction implements InstructionInterface {
    private $variableName;
    private $source;
    private FrameManager $frameManager;

    public function __construct($variableName, $source, FrameManager $frameManager) {
        $this->variableName = $variableName;
        $this->source = $source;
        $this->frameManager = $frameManager;
    }

    public function execute(): ?int
    {
        $value = $this->frameManager->getSourceValue($this->source);

        // Use mb_chr to convert the integer to a Unicode character
        $character = mb_chr($value, 'UTF-8');


        if ($character == false) {
            throw new StringException;
        }
    


        // Set the result in the target variable
        $this->frameManager->storeResult($this->variableName, $character);

        return null;
    }
}
