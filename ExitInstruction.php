<?php

namespace IPP\Student;

use IPP\Student\Exception\FrameAccessException;
use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\InvalidOperandValueException;

class ExitInstruction implements InstructionInterface {
    private $source;
    private FrameManager $frameManager;

    public function __construct($source, FrameManager $frameManager) {
        $this->source = $source;
        $this->frameManager = $frameManager;
    }

    public function execute(): ?int {
        // Resolve the source value
        $value = $this->resolveOperandValue($this->source);
        if ($this->frameManager->determineType($value) !== 'int') {
            throw new OperandTypeException;
        }

        if ($value >= 0 && $value <= 9) {
            return $value;
        }
        throw new InvalidOperandValueException;
        
    }

    private function resolveOperandValue($operand) {
        $type = $operand['type'];
        $value = $operand['value'];

        switch ($type) {
            case 'var':
                // Resolve frame and variable
                [$framePrefix, $variableName] = explode('@', $value, 2);
                $frame = $this->frameManager->getFrameBasedOnPrefix($framePrefix);
                if (!$frame) {
                    throw new FrameAccessException("Frame '{$framePrefix}' does not exist.");
                }
                return $frame->getVariable($variableName);
            case 'int':
                return $value;
            default:
                throw new OperandTypeException;
        }
    }

}
