<?php

namespace IPP\Student;

use IPP\Core;
use IPP\Student\Exception\FrameAccessException;

class DefvarInstruction implements InstructionInterface {
    private string $variableFullName;
    private FrameManager $frameManager;

    /**
     * @param string $variableFullName Full name of the variable including the frame prefix (e.g., "GF@counter").
     * @param FrameManager $frameManager The frame manager that handles the frames.
     */
    public function __construct(string $variableFullName, FrameManager $frameManager) {
        $this->variableFullName = $variableFullName;
        $this->frameManager = $frameManager;
    }

    /**
     * Executes the DEFVAR instruction logic.
     * 
     * @throws FrameAccessException If the frame is not initialized or the variable name format is incorrect.
     */
    public function execute(): ?int {
        [$framePrefix, $variableName] = explode('@', $this->variableFullName, 2);


        $frame = $this->frameManager->getFrameBasedOnPrefix($framePrefix);
        
        if (!$frame) {
            throw new FrameAccessException;
        }

        $frame->defineVariable($variableName);
        return null;
    }

}