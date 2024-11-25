<?php

namespace IPP\Student;

use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\FrameAccessException;
use IPP\Student\Exception\SemanticException;
use IPP\Student\Exception\InvalidOperandValueException;

class SubsInstruction implements InstructionInterface
{
    private $destinationVarName;
    private FrameManager $frameManager;

    public function __construct($destinationVarName, FrameManager $frameManager)
    {
        $this->destinationVarName = $destinationVarName;
        $this->frameManager = $frameManager;
    }

    public function execute(): ?int
    {

        $value2 = DataStack::getInstance()->pop();
        $value1 = DataStack::getInstance()->pop();

        if (is_numeric($value1) && is_numeric($value2)) {
            $result = $value1 - $value2;
        } else {
            throw new OperandTypeException;
        }

        $this->frameManager->storeResult($this->destinationVarName, $result);
        return null;
    }

}