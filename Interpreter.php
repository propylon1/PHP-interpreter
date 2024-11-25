<?php

namespace IPP\Student;

use IPP\Core\AbstractInterpreter;
use IPP\Core\Exception\InternalErrorException;
use IPP\Core\Exception\NotImplementedException;
use IPP\Core\ReturnCode;
use IPP\Student\Exception\InvalidSourceStructureException;
use IPP\Student\Exception\SemanticException;
use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\VariableAccessException;
use IPP\Student\Exception\FrameAccessException;
use IPP\Student\Exception\MissingValueException;
use IPP\Student\Exception\InvalidOperandValueException;
use IPP\Student\Exception\StringException;
use IPP\Core\Interface\InputReader;

use DOMDocument;



class Interpreter extends AbstractInterpreter
{


    public function execute(): int
    {
        try {
            $dom = $this->source->getDOMDocument();
        } catch (\Exception $e) {
            throw new InvalidSourceStructureException;
        }
        $exitCode = ReturnCode::OK;
        $inputReader = $this->input;

        $frameManager = new FrameManager();
        $labelManager = LabelManager::getInstance();
        
        InstructionFactory::setFrameManager($frameManager);
        InstructionFactory::setLabelManager($labelManager);

        $sorted = $this->validateAndSortInstructions($dom);
        $realindex = 0;
        foreach ($sorted as $index => $instructionElement) {
            if (strtoupper($instructionElement->getAttribute('opcode')) === 'LABEL') {
                $args = $this->extractArguments($instructionElement);
                $labelManager->defineLabel($args[0]['value'], $realindex);
            }
            $realindex++;
        }
        
        $instructions = array_values($sorted);
        $currentInstructionIndex = 0;
        while ($currentInstructionIndex < count($instructions)) {
            $instructionElement = $instructions[$currentInstructionIndex];
            $opcode = strtoupper($instructionElement->getAttribute('opcode'));
            $args = $this->extractArguments($instructionElement);
        
            try {
                //Directly handle instructions that impact program flow
                //Labels already loaded at this point so skip them
                if ($opcode === 'LABEL') {
                    $currentInstructionIndex++;
                    continue;
                } elseif ($opcode === 'RETURN') {
                    $currentInstructionIndex = CallStack::getInstance()->pop();
                    continue;
                }

                $instruction = InstructionFactory::createInstruction($opcode, $args, $inputReader);
                if (strpos($opcode, "JUMP") === 0) {
                    $val = $instruction->execute();
                    if ($val != null) {
                        $currentInstructionIndex = $val;
                        continue; // Skip incrementing index here
                    }
                } elseif ($opcode === 'EXIT') {
                    $exitCode = $instruction->execute();
                    break;
                } elseif ($opcode === 'CALL') {
                    $currentInstructionIndex++;
                    CallStack::getInstance()->push($currentInstructionIndex);
                    $currentInstructionIndex = $instruction->execute();
                    continue;
                } else {
                    $instruction->execute();
                }
            } catch (SemanticException $e) {
                $exitCode = ReturnCode::SEMANTIC_ERROR;
                throw new SemanticException;
            } catch (OperandTypeException $e) {
                $exitCode = ReturnCode::OPERAND_TYPE_ERROR;
                throw new OperandTypeException;
            } catch (VariableAccessException $e) {
                $exitCode = ReturnCode::VARIABLE_ACCESS_ERROR;
                throw new VariableAccessException;
            } catch (FrameAccessException $e) {
                $exitCode = ReturnCode::FRAME_ACCESS_ERROR;
                throw new FrameAccessException;
            } catch (MissingValueException $e) {
                $exitCode = ReturnCode::VALUE_ERROR;
                throw new MissingValueException;
            } catch (InvalidOperandValueException $e) {
                $exitCode = ReturnCode::OPERAND_VALUE_ERROR;
                throw new InvalidOperandValueException;
            } catch (StringException $e) {
                $exitCode = ReturnCode::STRING_OPERATION_ERROR;
                throw new StringException;
            } catch (\Exception $e) {
                $exitCode = ReturnCode::INTERNAL_ERROR;
                throw new InternalErrorException;
            }
            $currentInstructionIndex++;
        }

        return $exitCode;
    }

    private function extractArguments(\DOMElement $instructionElement): array {
        $args = [];

        foreach ($instructionElement->childNodes as $child) {
            if ($child instanceof \DOMElement && strpos($child->nodeName, 'arg') === 0) {
                // Extract the argument type and value, assuming the structure is <argN type="...">value</argN>
                $type = $child->getAttribute('type');
                $value = trim($child->nodeValue);
                if ($type == 'string') {
                    // Match all \ followed by numbers and replace them with actual ASCII characters
                    $value = preg_replace_callback('/\\\\(\d+)/', function($matches) {
                        return chr((int) $matches[1]);
                    }, $value);
            }
                $args[] = ['type' => $type, 'value' => $value];

                
            }
        }

        return $args;
    }

    private function validateAndSortInstructions(DOMDocument $dom): array {
        $instructions = [];
        foreach ($dom->getElementsByTagName('instruction') as $element) {
            $order = $element->getAttribute('order');
            if (!is_numeric($order) || intval($order) != $order || $order < 0) {
                throw new InvalidSourceStructureException;
            }
            if (array_key_exists($order, $instructions)) {
                throw new InvalidSourceStructureException;
            }
            $instructions[$order] = $element;
        }
        ksort($instructions); // Sort by order
        return $instructions;
    }


}