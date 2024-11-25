<?php
// File: FrameInterface.php

namespace IPP\Student;

interface FrameInterface {
    public function defineVariable(string $name);
    public function setVariable(string $name, $value);
    public function getVariable(string $name): mixed;
    public function getAllVariables(): array;
}
