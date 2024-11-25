<?php

namespace IPP\Student;

use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\StringException;

class Int2CharsInstruction implements InstructionInterface {
    private $variableName;
    private FrameManager $frameManager;

    public function __construct($variableName, FrameManager $frameManager) {
        $this->variableName = $variableName;
        $this->frameManager = $frameManager;
    }

    public function execute(): ?int
    {
        $value = DataStack::getInstance()->pop();

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
