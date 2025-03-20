<?php
namespace IPP\Student\Instructions;

use IPP\Student\Instruction;
use IPP\Student\Interpreter;
use IPP\Student\VariableManagement;
use IPP\Core\Exception\InternalErrorException;

class PUSHS extends Instruction{

    public function __construct(int $order) {
        $this->opcode = "PUSHS";
        $this->operandsCount = 1;
        $this->expectedOperands = [
            ['operandType' => ['var', 'const'], 'dataType' => ['any']]
        ];
        parent::__construct($order);
    }

    public function execute(): void{
        $operands = $this->OperandsProcessing();
    
        $VariableManagement = VariableManagement::getInstance();


        if(count(Interpreter::$dataStack) < Interpreter::$dataStackLimit){
            $operandForPush = clone($operands[0]);
            array_push(Interpreter::$dataStack, $operandForPush);
        } else{
            throw new InternalErrorException("Error: Data stack overflow");
        }
        
    }

}

?>