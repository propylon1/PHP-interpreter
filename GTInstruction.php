<?php

namespace IPP\Student;

use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\FrameAccessException;
use IPP\Student\Exception\InvalidOperandValueException;

class GTInstruction implements InstructionInterface
{
    private $destinationVarName;
    private $operand1;
    private $operand2;
    private FrameManager $frameManager;

    public function __construct($destinationVarName, $operand1, $operand2, FrameManager $frameManager) {
        $this->destinationVarName = $destinationVarName;
        $this->operand1 = $operand1;
        $this->operand2 = $operand2;
        $this->frameManager = $frameManager;
    }

    public function execute(): ?int {
        // Extract and resolve the values of operands
        $value1 = $this->frameManager->getSourceValue($this->operand1);
        $value2 = $this->frameManager->getSourceValue($this->operand2);

        if ($this->frameManager->determineType($value1) == "bool" && $this->frameManager->determineType($value2) == "bool") {
            if (strtoupper($value1) == "FALSE" && strtoupper($value2) == "TRUE") {
                $result = false;
            } else {
                $result = true;
            }
        }
        elseif (($this->frameManager->determineType($value1) == "int" && $this->frameManager->determineType($value2) == "int") || ($this->frameManager->determineType($value1) == "string" && $this->frameManager->determineType($value2) == "string")) {
            $result = $value1 > $value2;
        } else {
            throw new OperandTypeException;
        }

        // Store the result in the destination variable
        $this->frameManager->storeResult($this->destinationVarName, $result ? 'true' : 'false');
        return null;
    }


}