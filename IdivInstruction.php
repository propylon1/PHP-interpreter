<?php

namespace IPP\Student;

use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\FrameAccessException;
use IPP\Student\Exception\InvalidOperandValueException;

class IdivInstruction implements InstructionInterface
{
    private $destinationVarName;
    private $operand1;
    private $operand2;
    private FrameManager $frameManager;

    public function __construct($destinationVarName, $operand1, $operand2, FrameManager $frameManager)
    {
        $this->destinationVarName = $destinationVarName;
        $this->operand1 = $operand1;
        $this->operand2 = $operand2;
        $this->frameManager = $frameManager;
    }

    public function execute(): ?int
    {
        // Extract and resolve the values of operands
        $value1 = $this->resolveOperandValue($this->operand1);
        $value2 = $this->resolveOperandValue($this->operand2);

        if (intval($value2) == 0) {
            throw new InvalidOperandValueException;
        }

        if (is_numeric($value1) && is_numeric($value2)) {
            $result = intdiv($value1, $value2);
        } else {
            throw new OperandTypeException;
        }

        // Store the result in the destination variable
        $this->frameManager->storeResult($this->destinationVarName, $result);
        return null;
    }

    private function resolveOperandValue($operand)
    {
        if ($operand['type'] === 'var') {
            [$framePrefix, $variableName] = explode('@', $operand['value'], 2);
            $frame = $this->frameManager->getFrameBasedOnPrefix($framePrefix);
            if (!$frame) {
                throw new FrameAccessException;
            }
            return $frame->getVariable($variableName);
        } elseif ($operand['type'] === 'int') {
            return $operand['value'];
        } else {
            throw new OperandTypeException;
        }
    }

}
