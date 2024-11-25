<?php

namespace IPP\Student;

use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\FrameAccessException;
use IPP\Student\Exception\InvalidOperandValueException;

class IdivsInstruction implements InstructionInterface
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

        if (intval($value2) == 0) {
            throw new InvalidOperandValueException;
        }

        if (is_numeric($value1) && is_numeric($value2)) {
            $result = intdiv($value1, $value2);
        } else {
            throw new OperandTypeException;
        }

        // Store the result in the destination variable
        $this->frameManager->storeResult($this->destinationVarName, $result);
        return null;
    }

}
