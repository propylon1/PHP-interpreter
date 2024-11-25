<?php

namespace IPP\Student;

use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\FrameAccessException;
use IPP\Student\Exception\InvalidOperandValueException;

class AndInstruction implements InstructionInterface
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

        if ($value1 != 'true' && $value1 != 'false') {
            throw new InvalidOperandValueException;
        }

        if ($value1 == 'true') {
            $value1 = true;
        } else {
            $value1 = false;
        }

        if ($value2 != 'true' && $value2 != 'false') {
            throw new InvalidOperandValueException;
        }

        if ($value2 == 'true') {
            $value2 = true;
        } else {
            $value2 = false;
        }

        $result = $value1 && $value2;

        if ($result) {
            $result = 'true';
        } else {
            $result = 'false';
        }

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
        } elseif ($operand['type'] === 'bool') {
            return $operand['value'];
        } else {
            throw new OperandTypeException;
        }
    }

}
