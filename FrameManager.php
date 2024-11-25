<?php
// File: FrameManager.php

namespace IPP\Student;

use IPP\Student\Exception\FrameAccessException;
use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\InvalidOperandValueException;

class FrameManager {
    private ?TempFrame $temporaryFrame = null;
    private array $localFrames = [];

    public function getGlobalFrame(): GlobalFrame {
        return FrameFactory::getGlobalFrame();
    }

    public function createTemporaryFrame() {
        $this->temporaryFrame = FrameFactory::createTempFrame();
    }

    public function getTempFrame(): ?TempFrame {
        return $this->temporaryFrame;
    }

    public function discardTemporaryFrame() {
        $this->temporaryFrame = null;
    }

    public function pushTemporaryFrameToLocals() {
        if ($this->temporaryFrame === null) {
            throw new FrameAccessException;
        }
        // Create a new LocalFrame instance.
        $localFrame = FrameFactory::createLocalFrame();

        // Transfer variables from the temporary frame to the new local frame.
        foreach ($this->temporaryFrame->getAllVariables() as $name => $value) {
            $localFrame->defineVariable($name);
            $localFrame->setVariable($name, $value);
        }

        // Push the new local frame onto the local frames stack.
        $this->localFrames[] = $localFrame;

        // Undefine the temporary frame.
        $this->discardTemporaryFrame();
    }

    public function popLocalFrame(): LocalFrame {
        if (empty($this->localFrames)) {
            throw new FrameAccessException;
        }
        return array_pop($this->localFrames);
    }

    public function getCurrentLocalFrame(): ?LocalFrame {
        return end($this->localFrames) ?: null;
    }

    public function isTemporaryFrameDefined(): bool {
        return $this->temporaryFrame !== null;
    }

    public function getFrameBasedOnPrefix(string $framePrefix): ?FrameInterface {
        switch ($framePrefix) {
            case 'GF':
                return $this->getGlobalFrame();
            case 'LF':
                return $this->getCurrentLocalFrame();
            case 'TF':
                return $this->getTempFrame();
            default:
                return null;
        }
    }


    //Dynamic type detection based on value
    public function determineType($value): string {
        if (is_bool($value) || $value == 'true' || $value == 'false') {
            return 'bool';
        } elseif (is_int($value) || (is_string($value) && ctype_digit($value))) {
            return 'int';
        } elseif (is_float($value) || (is_string($value) && is_numeric($value) && strpos($value, '.') !== false)) {
            return 'float';
        } else if ($value == 'nil') {
            return 'nil';
        } else {
            return 'string';
        }
    }

    /**
     * Gets the source value based on the operand type and value.
     * 
     * @return mixed The value of the source operand.
     * @throws OperandTypeException If the source type is invalid.
     * This function shall only be utilized if there are no serious restraint on data type provided
     * For each restraint on type, verify returned value type using determineType()
     * With more than one type restraint, implementing objects own resolve class is reccomended
     */
    public function getSourceValue($operand)
    {
        $type = $operand['type'];
        $value = $operand['value'];

        switch ($type) {
            case 'var':
                // Get the value of the variable from its frame.
                [$framePrefix, $variableName] = explode('@', $value, 2);
                $frame = $this->getFrameBasedOnPrefix($framePrefix);
                if (!$frame) {
                    throw new FrameAccessException;
                }
                return $frame->getVariable($variableName);
            case 'nil':
                if (strtolower($value) != "nil") {
                    throw new InvalidOperandValueException;
                }
                return $value;
            case 'int':
                if (is_numeric($value)) {
                    return $value;
                }
                throw new InvalidOperandValueException;
            case 'bool':
                if (strtoupper($value) === 'TRUE' || strtoupper($value) === 'FALSE') {
                    return $value;
                }
                throw new InvalidOperandValueException;
            case 'string':
                return $value;
            default:
                throw new OperandTypeException;
        }
    }

    //Helper function to store result in a desired variable
    public function storeResult($destinationVarName, $result)
    {
        [$framepref, $varName] = explode('@', $destinationVarName, 2);
        $frame = $this->getFrameBasedOnPrefix($framepref); // Resolve the correct frame

        if (!$frame) {
            throw new FrameAccessException;
        }
        $frame->setVariable($varName, $result);
    }
}