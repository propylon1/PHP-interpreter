<?php

namespace IPP\Student;

use IPP\Student\Exception\LabelNotFoundException;
use IPP\Student\Exception\SemanticException;

class JumpInstruction implements InstructionInterface
{
    private $label;
    private LabelManager $labelManager;

    public function __construct($label, LabelManager $labelManager)
    {
        $this->label = $label;
        $this->labelManager = $labelManager;
    }

    public function execute(): ?int
    {
        // Attempt to get the instruction index for the given label
        try {
            return $this->labelManager->getLabelIndex($this->label);
        } catch (SemanticException $e) {
            // Handle the case where the label does not exist
            throw new SemanticException;
        }
        
        // The actual jump will be handled by the interpreter, based on the target index.
    }

}
