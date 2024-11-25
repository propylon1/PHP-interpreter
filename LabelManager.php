<?php

namespace IPP\Student;

use IPP\Student\Exception\SemanticException;

class LabelManager {
    private static ?LabelManager $instance = null;
    private array $labels = [];

    // Private constructor to prevent creating a new instance from outside
    private function __construct() {}

    // Private clone method to prevent cloning the instance
    private function __clone() {}

    // Method to access the singleton instance
    public static function getInstance(): LabelManager {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Method to define a label
    public function defineLabel($labelName, $instructionIndex) {
        if (array_key_exists($labelName, $this->labels)) {
            throw new SemanticException("Label already defined: $labelName");
        }
        $this->labels[$labelName] = $instructionIndex;
    }

    // Method to get the index of a label
    public function getLabelIndex($labelName) {
        if (!array_key_exists($labelName, $this->labels)) {
            throw new SemanticException("Label not found: $labelName");
        }
        return $this->labels[$labelName];
    }
}
