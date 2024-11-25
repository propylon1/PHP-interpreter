<?php

namespace IPP\Student;
use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\FrameAccessException;
use IPP\Student\Exception\InvalidOperandValueException;
use IPP\Student\Exception\InvalidOperandTypeException;

class JumpIfEQInstruction implements InstructionInterface
{
    private $label;
    private $operand1;
    private $operand2;
    private LabelManager $labelManager;
    private FrameManager $frameManager;

    public function __construct($label, $operand1, $operand2, LabelManager $labelManager, FrameManager $frameManager)
    {
        $this->label = $label;
        $this->operand1 = $operand1;
        $this->operand2 = $operand2;
        $this->labelManager = $labelManager;
        $this->frameManager = $frameManager;
    }

    public function execute(): ?int
    {
        $value1 = $this->frameManager->getSourceValue($this->operand1);
        $value2 = $this->frameManager->getSourceValue($this->operand2);
        if ($this->frameManager->determineType($value1) === $this->frameManager->determineType($value2)) {

            if ($value1 === $value2) {
                return $this->labelManager->getLabelIndex($this->label);
            }

            return null;
        } elseif ($this->frameManager->determineType($value1) === 'nil' || $this->frameManager->determineType($value2) === 'nil') {

            if ($value1 === $value2) {
                return $this->labelManager->getLabelIndex($this->label);
            }

            return null;
        } else {
            throw new OperandTypeException;
        }
    }

    

}
