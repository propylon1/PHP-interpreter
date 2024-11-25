<?php

namespace IPP\Student;
use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\FrameAccessException;
use IPP\Student\Exception\InvalidOperandValueException;

class MoveInstruction implements InstructionInterface {
    private string $dest;
    private $source;
    private FrameManager $frameManager;

    /**
     * @param string $dest Destination variable including the frame prefix (e.g., "GF@counter").
     * @param mixed $source Source operand, which can be a variable or a constant.
     * @param FrameManager $frameManager The frame manager handling the frames.
     */
    public function __construct(string $dest, $source, FrameManager $frameManager) {
        $this->dest = $dest;
        $this->source = $source;
        $this->frameManager = $frameManager;
    }

    /**
     * Executes the MOVE instruction logic.
     */
    public function execute(): ?int
    {
        // Determine the source value based on its type and value.
        $sourceValue = $this->getSourceValue();

        [$framePrefix, $variableName] = explode('@', $this->dest, 2);

        // Get the destination frame based on the frame prefix.
        $frame = $this->frameManager->getFrameBasedOnPrefix($framePrefix);

        if (!$frame) {
            throw new FrameAccessException;
        }

        // Set the variable in the destination frame to the source value.
        $frame->setVariable($variableName, $sourceValue);
        return null;
    }

    /**
     * Gets the source value based on the operand type and value.
     * 
     * @return mixed The value of the source operand.
     * @throws OperandTypeException If the source type is invalid.
     */
    private function getSourceValue()
    {
        $type = $this->source['type'];
        $value = $this->source['value'];

        switch ($type) {
            case 'var':
                // Get the value of the variable from its frame.
                [$framePrefix, $variableName] = explode('@', $value, 2);
                $frame = $this->frameManager->getFrameBasedOnPrefix($framePrefix);
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

}