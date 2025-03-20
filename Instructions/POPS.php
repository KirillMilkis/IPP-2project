<?php
namespace IPP\Student\Instructions;

use IPP\Student\Instruction;
use IPP\Student\Interpreter;
use IPP\Student\VariableManagement;
use IPP\Student\Constant;
use IPP\Student\Exception\ValueException;

class POPS extends Instruction{

    public function __construct(int $order) {
        $this->opcode = "POPS";
        $this->operandsCount = 1;
        $this->expectedOperands = [
            ['operandType' => ['var'], 'dataType' => ['any']],
        ];
        parent::__construct($order);
    }

    public function execute(): void{
        $operands = $this->OperandsProcessing();
       
        $VariableManagement = VariableManagement::getInstance();

        if(count(Interpreter::$dataStack) > 0){
            $poppedOperand = array_pop(Interpreter::$dataStack);
            $VariableManagement->setValue($operands[0], new Constant('const', $poppedOperand->value, $poppedOperand->dataType));
        } else{
            throw new ValueException("Error: Data stack is empty");
        }
    } 
        
}

?>