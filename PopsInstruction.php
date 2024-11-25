<?php

namespace IPP\Student;

use IPP\Core;
use IPP\Student\Exception\FrameAccessException;
use IPP\Student\Exception\MissingValueException;

class PopsInstruction implements InstructionInterface {
    private $destinationVar;
    private FrameManager $frameManager;

    /**
     * PopsInstruction constructor.
     * @param string $destinationVar Destination variable in the format <frame>@<name>
     * @param FrameManager $frameManager The frame manager handling the frames.
     */
    public function __construct($destinationVar, FrameManager $frameManager) {
        $this->destinationVar = $destinationVar;
        $this->frameManager = $frameManager;
    }

    /**
     * Executes the POPS instruction logic.
     * 
     * This method pops a value from the data stack and stores it in the given variable.
     * @throws FrameAccessException If accessing a frame fails.
     * @throws MissingValueException If the data stack is empty.
     * And yes, using singleton in this context is incorrect
     */
    public function execute(): ?int {
        // Access the DataStack singleton instance directly
        $dataStack = DataStack::getInstance();

        // Pop the value from the data stack
        $value = $dataStack->pop();

        // Determine the frame and variable name from the destination variable
        [$framePrefix, $variableName] = explode('@', $this->destinationVar, 2);

        // Get the appropriate frame based on the prefix
        $frame = $this->frameManager->getFrameBasedOnPrefix($framePrefix);
        if (!$frame) {
            throw new FrameAccessException;
        }
        $frame->setVariable($variableName, $value);
        return null;
    }


}