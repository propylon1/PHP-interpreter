<?php

namespace IPP\Student;

use IPP\Core;
use IPP\Student\Exception\FrameAccessException;


class CreateframeInstruction implements InstructionInterface
{

    private FrameManager $frameManager;


    public function __construct(FrameManager $frameManager)
    {
        $this->frameManager = $frameManager;
    }

    public function execute(): ?int
    {
        $this->frameManager->createTemporaryFrame();
        return null;
    }
}