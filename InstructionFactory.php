<?php

namespace IPP\Student;

use IPP\Student\Exception\SemanticException;
use IPP\Core\Interface\InputReader;
use IPP\Student\Exception\OperandTypeException;

/**
 * Factory for creating instruction objects.
 */
class InstructionFactory {

    private static ?FrameManager $frameManager = null;
    private static ?LabelManager $labelManager = null;

    public static function setFrameManager(FrameManager $frameManager): void {
        self::$frameManager = $frameManager;
    }

    public static function setLabelManager(LabelManager $labelManager): void {
        self::$labelManager = $labelManager;
    }

    public static function createInstruction($opcode, $args, $inputReader): InstructionInterface {
        switch ($opcode) {
            case 'MOVE':
                if (count($args) !== 2) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new MoveInstruction($variableFullName, $args[1], self::$frameManager);
            case "DEFVAR":
                if (count($args) !== 1) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new DefvarInstruction($variableFullName, self::$frameManager);
            case "CREATEFRAME":
                if (count($args) !== 0) {
                    throw new SemanticException;
                }
                return new CreateframeInstruction(self::$frameManager);
            case "PUSHFRAME":
                if (count($args) !== 0) {
                    throw new SemanticException;
                }
                return new PushframeInstruction(self::$frameManager);
            case "POPFRAME":
                if (count($args) !== 0) {
                    throw new SemanticException;
                }
                return new PopframeInstruction(self::$frameManager);
            case "PUSHS":
                if (count($args) !== 1) {
                    throw new SemanticException;
                }
                return new PushsInstruction($args[0], self::$frameManager);
            case "POPS":
                if (count($args) !== 1) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new PopsInstruction($variableFullName, self::$frameManager);
            case "ADD":
                if (count($args) !== 3) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new AddInstruction($variableFullName, $args[1], $args[2], self::$frameManager);
            case "SUB":
                if (count($args) !== 3) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new SubInstruction($variableFullName, $args[1], $args[2], self::$frameManager);
            case "MUL":
                if (count($args) !== 3) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new MulInstruction($variableFullName, $args[1], $args[2], self::$frameManager);
            case "IDIV":
                if (count($args) !== 3) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new IdivInstruction($variableFullName, $args[1], $args[2], self::$frameManager);
            case "LT":
                if (count($args) !== 3) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new LTInstruction($variableFullName, $args[1], $args[2], self::$frameManager);
           case "GT":
                if (count($args) !== 3) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new GTInstruction($variableFullName, $args[1], $args[2], self::$frameManager);
            case "EQ":
                if (count($args) !== 3) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new EQInstruction($variableFullName, $args[1], $args[2], self::$frameManager);
            case "WRITE":
                if (count($args) !== 1) {
                    throw new SemanticException;
                }
                return new WriteInstruction($args[0], self::$frameManager);
            case "JUMP":
                    if (count($args) !== 1) {
                        throw new SemanticException;
                    }
                    $labelName = self::getArgValue($args[0]);
                    return new JumpInstruction($labelName, self::$labelManager);
            case "JUMPIFEQ":
                if (count($args) !== 3) {
                    throw new SemanticException;
                }
                $labelName = self::getArgValue($args[0]);
                return new JumpIfEQInstruction($labelName, $args[1], $args[2], self::$labelManager, self::$frameManager);
            case "JUMPIFNEQ":
                if (count($args) !== 3) {
                    throw new SemanticException;
                }
                $labelName = self::getArgValue($args[0]);
                return new JumpIfNEQInstruction($labelName, $args[1], $args[2], self::$labelManager, self::$frameManager);
            case "EXIT":
                if (count($args) !== 1) {
                    throw new SemanticException;
                }
                return new ExitInstruction($args[0], self::$frameManager);
            case "CALL":
                if (count($args) !== 1) {
                    throw new SemanticException;
                }
                $labelName = self::getArgValue($args[0]);
                return new CallInstruction($labelName, self::$labelManager);
            case "AND":
                if (count($args) !== 3) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new AndInstruction($variableFullName, $args[1], $args[2], self::$frameManager);
            case "OR":
                if (count($args) !== 3) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new OrInstruction($variableFullName, $args[1], $args[2], self::$frameManager);
            case "NOT":
                if (count($args) !== 2) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new NotInstruction($variableFullName, $args[1], self::$frameManager);
            case "INT2CHAR":
                if (count($args) !== 2) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new Int2CharInstruction($variableFullName, $args[1], self::$frameManager);
            case "STRI2INT":
                if (count($args) !== 3) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new Stri2IntInstruction($variableFullName, $args[1], $args[2], self::$frameManager);
            case "READ":
                if (count($args) !== 2) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new ReadInstruction($variableFullName, $args[1], $inputReader, self::$frameManager);
            case "CONCAT":
                if (count($args) !== 3) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new ConcatInstruction($variableFullName, $args[1], $args[2], self::$frameManager);
            case "STRLEN":
                if (count($args) !== 2) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new StrlenInstruction($variableFullName, $args[1], self::$frameManager);
            case "GETCHAR":
                if (count($args) !== 3) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new GetcharInstruction($variableFullName, $args[1], $args[2], self::$frameManager);
            case "SETCHAR":
                if (count($args) !== 3) {
                    throw new SemanticException;
                }
                if ($args[0]['type'] != 'var') {
                    throw new OperandTypeException;
                }
                return new SetcharInstruction($args[0], $args[1], $args[2], self::$frameManager);
            case 'DPRINT':
                if (count($args) !== 1) {
                    throw new SemanticException;
                }
                return new DprintInstruction($args[0], self::$frameManager);
            case 'BREAK':
                if (count($args) !== 0) {
                    throw new SemanticException;
                }
                return new BreakInstruction();
            case 'TYPE':
                if (count($args) !== 2) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new TypeInstruction($variableFullName, $args[1], self::$frameManager);
            case 'CLEARS':
                if (count($args) !== 0) {
                    throw new SemanticException;
                }
                return new ClearsInstruction();
            case 'ADDS':
                if (count($args) !== 1) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new AddsInstruction($variableFullName, self::$frameManager);
            case 'SUBS':
                if (count($args) !== 1) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new SubsInstruction($variableFullName, self::$frameManager);
            case 'MULS':
                if (count($args) !== 1) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new MulsInstruction($variableFullName, self::$frameManager);
            case 'IDIVS':
                if (count($args) !== 1) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new IdivsInstruction($variableFullName, self::$frameManager);
            case 'LTS':
                if (count($args) !== 1) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new LTsInstruction($variableFullName, self::$frameManager);
            case 'GTS':
                if (count($args) !== 1) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new GTsInstruction($variableFullName, self::$frameManager);
            case 'EQS':
                if (count($args) !== 1) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new EQsInstruction($variableFullName, self::$frameManager);
            case 'ANDS':
                if (count($args) !== 1) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new AndsInstruction($variableFullName, self::$frameManager);
            case 'ORS':
                if (count($args) !== 1) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new OrsInstruction($variableFullName, self::$frameManager);
            case 'NOTS':
                if (count($args) !== 1) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new NotsInstruction($variableFullName, self::$frameManager);
            case 'INT2CHARS':
                if (count($args) !== 1) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new Int2CharsInstruction($variableFullName, self::$frameManager);
            case 'STRI2INTS':
                if (count($args) !== 1) {
                    throw new SemanticException;
                }
                $variableFullName = self::extractVariableName($args[0]);
                return new Stri2IntsInstruction($variableFullName, self::$frameManager);
            case 'JUMPIFEQS':
                if (count($args) !== 1) {
                    throw new SemanticException;
                }
                $labelName = self::getArgValue($args[0]);
                return new JumpIfEQsInstruction($labelName, self::$labelManager, self::$frameManager);
            case 'JUMPIFNEQS':
                if (count($args) !== 1) {
                    throw new SemanticException;
                }
                $labelName = self::getArgValue($args[0]);
                return new JumpIfNEQsInstruction($labelName, self::$labelManager, self::$frameManager);
            default:
                throw new SemanticException;
        }
    }

    // Helper method to extract the variable name from the argument
    private static function extractVariableName(array $arg): string {
        if ($arg['type'] !== 'var' || !isset($arg['value'])) {
            throw new SemanticException;
        }
        return $arg['value'];
    }


    private static function getArgValue(array $arg): string {
        if (!isset($arg['value'])) {
            throw new SemanticException;
        }
        return $arg['value'];
    }

}




