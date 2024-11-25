<?php

namespace IPP\Student;

use IPP\Core;
use IPP\Student\Exception\FrameAccessException;

class PopframeInstruction implements InstructionInterface {
    private FrameManager $frameManager;

    public function __construct(FrameManager $frameManager) {
        $this->frameManager = $frameManager;
    }

    public function execute(): ?int {
        // Check if there is a local frame to pop.
        if (!$this->frameManager->getCurrentLocalFrame()) {
            throw new FrameAccessException;
        }

        // Pop the local frame to temporary frame
        $poppedFrame = $this->frameManager->popLocalFrame();

        // Ensure the temporary frame is undefined before assignment
        if ($this->frameManager->isTemporaryFrameDefined()) {
            throw new FrameAccessException;
        }

        // Transfer variables from the popped local frame to the temporary frame.
        $this->frameManager->createTemporaryFrame();
        $temporaryFrame = $this->frameManager->getTempFrame();
        
        foreach ($poppedFrame->getAllVariables() as $name => $value) {
            $temporaryFrame->defineVariable($name);
            $temporaryFrame->setVariable($name, $value);
        }
        return null;
    }
}