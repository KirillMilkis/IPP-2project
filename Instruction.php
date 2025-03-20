<?php

namespace IPP\Student;

use DOMElement;
use IPP\Student\Operand;
use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\SourceStructureException;
use IPP\Student\Exception\VariableAccessException;
use IPP\Student\VariableManagement;

class Instruction {

    public string $opcode;
    public int $operandsCount;
    /** @var array<int, array<string, array<int, string>>>*/
    protected $expectedOperands;
    /** @var array<int, Variable|Constant|LabelOperand|Type> */
    protected $operands;
    protected int $order;


    public function __construct(int $order) {
        $this->operands = array();
        $this->order = $order;
    }

    /**
     * Method is used for validation in 2 cases: to to find a match for operandType and dataType between existing and expected operands.
     * Method takes expectedSomeTypes where we have all possible operand types and data types for instr operands.
     * @param string $operandSomeType
     * @param array<string> $expectedSomeTypes
     * @param callable $errorCreate
     */
    private function validateOperand(string $operandSomeType,array $expectedSomeTypes,callable $errorCreate): void{
        $isValid = false;
        for($j = 0; $j < count($expectedSomeTypes); $j++) {
            if($operandSomeType == $expectedSomeTypes[$j]) {
                // If at least 1 expected type matches, then it is valid.
                $isValid = true;
            }
            if($isValid){
                break;
            }
        }
        // Throw different errors in 2 cases (operandType and dataType validation)
        if(!$isValid) {
            throw $errorCreate();
        }
    }

    /**
     * Method generally calls at the beginning of each instruction execute method. It creates copies from operands o make sure that the original arguments 
     * in the instruction are not accidentally changed. It call method to search necessary and valid operands and data types.
     * @return array<Variable|Constant|Type|LabelOperand>
     */
    protected function &OperandsProcessing(): array{
        $copiedOperands = array_map(function($object) {
            return clone $object;
        }, $this->operands);
        $varMan = VariableManagement::getInstance();
        $varMan->assignDefinedVariables($copiedOperands);
        $this->checkOperands($copiedOperands);
        return $copiedOperands;
    }

    /**
     * Method check if the number of operands is correct and than iterate every instr argument and call validateOperand method to validate it.
     * @param array<Variable|Constant|Type|LabelOperand> $operands
     */
    protected function checkOperands(array $operands): void{
        // Error which can be called only in validateOperand method (two different cases).
        $errorOperandType = function(){
            return new SourceStructureException("Error: Invalid operand type");
        };
        $errorDataType = function(){
            return new OperandTypeException("Error: Invalid data type");
        };
        // Check number of argument in the node entry, because the arguments can go in different order, example: <arg1 type="..."></arg1>
        $maximumNumInTag = !empty($operands) ? max(array_keys($operands)) : -1;
        if(count($operands) !== $this->operandsCount || $maximumNumInTag !== $this->operandsCount - 1) {
            throw new SourceStructureException("Error: Invalid number of arguments");
        }
        // Iterate every argument operand
        for($i = 0; $i < count($operands); $i++) {
            
            $this->validateOperand($operands[$i]->operandType, $this->expectedOperands[$i]['operandType'], $errorOperandType);

            if($this->expectedOperands[$i]['dataType'][0] != "null" && $this->expectedOperands[$i]['dataType'][0] != "any") {
                $this->validateOperand($operands[$i]->dataType, $this->expectedOperands[$i]['dataType'], $errorDataType);
            } 

        }
    }

    /**
     * Method calls only in arith instr. such as MUL, ADD etc. It checks if the operands are integers and returns their values.
     * @param array<Variable|Constant> $arithmeticOperands
     * @return array<int>
     */
    protected static function checkArithmeticOperands(array $arithmeticOperands) : array{
        if($arithmeticOperands[0]->dataType != 'int' || $arithmeticOperands[1]->dataType != 'int'){
            throw new OperandTypeException("Error: Invalid data type in arithemetic operation");
        }

        $value1 = $arithmeticOperands[0]->value;
        $value2 = $arithmeticOperands[1]->value;

        return array($value1, $value2);
    }

    /**
     * Method calls only in logical instr. such as EQ, LT etc. It checks if the operands are the same data type and returns their values.
     * @param array<Variable|Constant> $logicalOperands
     * @param bool $isEQ // Flag which indicate instruction EQ
     * @return array<int>
     */
    protected static function checkLogicalOperands(array $logicalOperands, bool $isEQ) : array{
        if ($logicalOperands[0]->dataType != $logicalOperands[1]->dataType) {
            // If operands have different data types, they can be nil only if the instruction is EQ.
            if ($isEQ && ($logicalOperands[0]->dataType != 'nil' && $logicalOperands[1]->dataType != 'nil')) {
                throw new OperandTypeException("Error: Data types do not match for logical operation");
            // If operands have different data types and the instruction is not EQ, it is an error.
            } else if (!$isEQ) {
                throw new OperandTypeException("Error: Data types do not match for logical operation");
            }
        }

        $value1 = $logicalOperands[0]->value;
        $value2 = $logicalOperands[1]->value;

        return array($value1, $value2);
    }

    /**
     * Method that called from interpreter class to read arguments and operands to the instruction.
     * @param DOMElement $operandNode
     * @return void
     */ 
    public function addOperands(DOMElement $operandNode, int $argNumInTag): void{
        $type = $operandNode->getAttribute('type');
        $value = $operandNode->nodeValue;
        // Calling method which can return Variable, Constant, Type or Label instance.
        $newOperand = Operand::operandBuild($type, $value);         
        $this->operands[$argNumInTag - 1] = $newOperand;
        
    }

    

}
?>