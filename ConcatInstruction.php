<?php

namespace IPP\Student;

use IPP\Student\FrameManager;
use IPP\Student\Exception\StringException;
use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\FrameAccessException;

class ConcatInstruction implements InstructionInterface {
    private $resultVar;
    private $string1;
    private $string2;
    private FrameManager $frameManager;

    public function __construct($resultVar, $string1, $string2, FrameManager $frameManager) {
        $this->resultVar = $resultVar;
        $this->string1 = $string1;
        $this->string2 = $string2;
        $this->frameManager = $frameManager;
    }

    public function execute(): ?int {

        // Resolve values for operands, ensure they are strings
        $val1 = $this->resolveOperandValue($this->string1);
        $val2 = $this->resolveOperandValue($this->string2);


        // Concatenate the strings
        $result = $val1 . $val2;

        // Store the result in the specified variable
        $this->frameManager->storeResult($this->resultVar, $result);
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
        } elseif ($operand['type'] === 'string') {
            return $operand['value'];
        } else {
            throw new OperandTypeException;
        }
    }
}
