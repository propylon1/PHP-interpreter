<?php

namespace IPP\Student;

/**
 * BreakInstruction is designed for debugging purposes.
 * It outputs the current state of all active frames and the data stack.
 */
class BreakInstruction implements InstructionInterface
{

    public function __construct()
    {
    }


    public function execute(): ?int
    {
        fwrite(STDERR, "Break Called ");
        return null;
    }
}
