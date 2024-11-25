<?php

namespace IPP\Student;

use IPP\Student\Exception\MissingValueException;

class DataStack {
    private static ?DataStack $instance = null;
    private array $stack = [];

    private function __construct() {
        // Private constructor to prevent direct instantiation.
    }

    public static function getInstance(): DataStack {
        if (self::$instance === null) {
            self::$instance = new DataStack();
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
