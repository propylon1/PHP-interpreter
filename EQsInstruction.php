<?php

namespace IPP\Student;

use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\FrameAccessException;
use IPP\Student\Exception\InvalidOperandValueException;

class EQsInstruction implements InstructionInterface
{
    private $destinationVarName;
    private FrameManager $frameManager;

    public function __construct($destinationVarName, FrameManager $frameManager) {
        $this->destinationVarName = $destinationVarName;
        $this->frameManager = $frameManager;
    }

    public function execute(): ?int {
        // Extract and resolve the values of operands
        $value2 = DataStack::getInstance()->pop();
        $value1 = DataStack::getInstance()->pop();

        if ($this->frameManager->determineType($value1) == "bool" && $this->frameManager->determineType($value2) == "bool") {
            if (strtoupper($value1) == strtoupper($value2)) {
                $result = true;
            } else {
                $result = false;
            }
        } elseif ($this->frameManager->determineType($value1) == "nil" && $this->frameManager->determineType($value2) == "nil") {
            $result = true;
        } elseif (($this->frameManager->determineType($value1) == "int" && $this->frameManager->determineType($value2) == "int") || ($this->frameManager->determineType($value1) == "int" && $this->frameManager->determineType($value2) == "int")) {
            $result = ($value1 == $value2);
        } else {
            throw new OperandTypeException;
        }

        // Store the result in the destination variable
        $this->frameManager->storeResult($this->destinationVarName, $result ? 'true' : 'false');
        return null;
    }


}