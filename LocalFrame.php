<?php
// File: GlobalFrame.php

namespace IPP\Student;

use IPP\Student\Exception\SemanticException;
use IPP\Student\Exception\VariableAccessException;

class LocalFrame implements FrameInterface {
    private array $variables = [];

    public function defineVariable(string $name) {
        if (array_key_exists($name, $this->variables)) {
            throw new SemanticException;
        }
        $this->variables[$name] = null;
    }

    public function setVariable(string $name, $value) {
        if (!array_key_exists($name, $this->variables)) {
            throw new VariableAccessException;
        }
        $this->variables[$name] = $value;
    }

    public function getVariable(string $name): mixed {
        if (!array_key_exists($name, $this->variables)) {
            throw new VariableAccessException;
        }
        return $this->variables[$name];
    }
    public function getAllVariables(): array {
        return $this->variables;
    }
}
