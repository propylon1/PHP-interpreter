<?php

namespace IPP\Student;

/**
 * Interface for all instructions.
 */
interface InstructionInterface {
    public function execute(): ?int;
}
