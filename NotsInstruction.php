<?php

namespace IPP\Student;

use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\FrameAccessException;
use IPP\Student\Exception\InvalidOperandValueException;

class NotsInstruction implements InstructionInterface
{
    private $destinationVarName;
    private FrameManager $frameManager;

    public function __construct($destinationVarName, FrameManager $frameManager)
    {
        $this->destinationVarName = $destinationVarName;
        $this->frameManager = $frameManager;
    }

    public function execute(): ?int
    {
        // Extract and resolve the values of operands
        $value1 = DataStack::getInstance()->pop();

        if ($value1 != 'true' && $value1 != 'false') {
            throw new InvalidOperandValueException;
        }

        if ($value1 == 'true') {
            $value1 = true;
        } else {
            $value1 = false;
        }

        $result = !$value1;

        if ($result) {
            $result = 'true';
        } else {
            $result = 'false';
        }

        // Store the result in the destination variable
        $this->frameManager->storeResult($this->destinationVarName, $result);
        return null;
    }
}
