<?php

namespace IPP\Student;

use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\FrameAccessException;
use IPP\Student\Exception\InvalidOperandValueException;

class NotInstruction implements InstructionInterface
{
    private $destinationVarName;
    private $operand1;
    private FrameManager $frameManager;

    public function __construct($destinationVarName, $operand1, FrameManager $frameManager)
    {
        $this->destinationVarName = $destinationVarName;
        $this->operand1 = $operand1;
        $this->frameManager = $frameManager;
    }

    public function execute(): ?int
    {
        // Extract and resolve the values of operands
        $value1 = $this->resolveOperandValue($this->operand1);

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
