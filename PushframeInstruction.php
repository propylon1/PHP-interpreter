<?php

namespace IPP\Student;

use IPP\Core;
use IPP\Student\Exception\FrameAccessException;


class PushframeInstruction implements InstructionInterface {
    private FrameManager $frameManager;

    /**
     * PushFrameInstruction constructor.
     * @param FrameManager $frameManager The frame manager handling the frames.
     */
    public function __construct(FrameManager $frameManager) {
        $this->frameManager = $frameManager;
    }

    /**
     * Executes the PUSHFRAME instruction logic.
     * 
     * This method attempts to push the current temporary frame to the local frame stack.
     * It throws an exception if the temporary frame is not defined.
     * 
     * @throws FrameAccessException If the temporary frame is not defined.
     */
    public function execute(): ?int {

        // Push the temporary frame to the local frame stack
        $this->frameManager->pushTemporaryFrameToLocals();

        // Undefine the temporary frame
        $this->frameManager->discardTemporaryFrame();
        return null;
    }
}