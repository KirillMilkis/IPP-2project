<?php

namespace IPP\Student\Instructions;

use IPP\Student\Instruction;
use IPP\Student\VariableManagement;
use IPP\Student\Exception\SemanticException;

class DEFVAR extends Instruction{

    public function __construct(int $order) {
        $this->opcode = "DEFVAR";
        $this->operandsCount = 1;
        $this->expectedOperands = [
            ['operandType' => ['var'], 'dataType' => ['any']],
        ];
        parent::__construct($order);
    }
    
    public function execute():void{
        // This function does not need to assign defined variables because it will cause an error
        // This function DEFVAR define variables by itself
        $operands = array_map(function($object) {
            return clone $object;
        }, $this->operands);
        $this->checkOperands($operands);
        $VariableManagement = VariableManagement::getInstance();

        if(!$VariableManagement->checkVariableDefinition($operands[0]->name,$operands[0]->frame)){
            $VariableManagement->defineVariable($operands[0]);
        } else{
            throw new SemanticException("Error: Variable already defined");
        }
        
        
    }


}

?>