<?php

namespace IPP\Student;
use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\FrameAccessException;
use IPP\Student\Exception\InvalidOperandValueException;
use IPP\Student\Exception\InvalidOperandTypeException;

class JumpIfNEQsInstruction implements InstructionInterface
{
    private $label;
    private LabelManager $labelManager;
    private FrameManager $frameManager;

    public function __construct($label, LabelManager $labelManager, FrameManager $frameManager)
    {
        $this->label = $label;
        $this->labelManager = $labelManager;
        $this->frameManager = $frameManager;
    }

    public function execute(): ?int
    {
        $value2 = DataStack::getInstance()->pop();
        $value1 = DataStack::getInstance()->pop();
        
        if ($this->frameManager->determineType($value1) === $this->frameManager->determineType($value2)) {

            if ($value1 !== $value2) {
                return $this->labelManager->getLabelIndex($this->label);
            }

            return null;
        } elseif ($this->frameManager->determineType($value1) === 'nil' || $this->frameManager->determineType($value2) === 'nil') {

            if ($value1 !== $value2) {
                return $this->labelManager->getLabelIndex($this->label);
            }

            return null;
        } else {
            throw new OperandTypeException;
        }
    }

    

}