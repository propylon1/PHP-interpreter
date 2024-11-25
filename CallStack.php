<?php

namespace IPP\Student;

use IPP\Student\Exception\MissingValueException;

class CallStack {
    private static ?CallStack $instance = null;
    private array $stack = [];

    private function __construct() {
        // Private constructor to prevent direct instantiation.
    }

    public static function getInstance(): CallStack {
        if (self::$instance === null) {
            self::$instance = new CallStack();
        }
        return self::$instance;
    }

    public function push($value) {
        array_push($this->stack, $value);
    }

    public function pop() {
        if (empty($this->stack)) {
            throw new MissingValueException;
        }
        return array_pop($this->stack);
    }

}