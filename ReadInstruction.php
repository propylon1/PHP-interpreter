<?php

namespace IPP\Student;

use IPP\Core\AbstractInterpreter;
use IPP\Core\FileInputReader;
use IPP\Core\Settings;
use IPP\Student\Exception\SemanticException;
use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\InvalidOperandValueException;
use IPP\Core\Interface\InputReader;

/**
 * Implements the READ instruction from IPPcode24.
 */
class ReadInstruction implements InstructionInterface {
    private $var;
    private $type;
    private FileInputReader $inputReader;
    private FrameManager $frameManager;


    public function __construct($var, $type, $inputReader,FrameManager $frameManager) {
        $this->var = $var;
        $this->type = $type;
        $this->inputReader = $inputReader;
        $this->frameManager = $frameManager;
    }

    public function execute(): ?int {
        if ($this->type['type'] !== 'type') {
            throw new OperandTypeException;
        }
        $type = $this->type['value'];
        switch ($type) {
            case 'int':
                $value = $this->inputReader->readInt();
                break;
            case 'bool':
                $value = $this->inputReader->readBool();
                break;
            case 'string':
                $value = $this->inputReader->readString();
                break;
            default:
                throw new OperandTypeException;
        }

        if ($value === null) {
            $value = 'nil';
        }

        $this->frameManager->storeResult($this->var, $value);
        return null;
    }
}
