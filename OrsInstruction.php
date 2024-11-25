<?php

namespace IPP\Student;

use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\FrameAccessException;
use IPP\Student\Exception\InvalidOperandValueException;

class OrsInstruction implements InstructionInterface
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

        $result = $value1 || $value2;

        if ($result) {
            $result = 'true';
        } else {
            $result = 'false';
        }

        $this->frameManager->storeResult($this->destinationVarName, $result);
        return null;
    }

}