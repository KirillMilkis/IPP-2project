<?php
namespace IPP\Student\Instructions;

use IPP\Student\Instruction;
use IPP\Student\FlowManagement;
use IPP\Student\Exception\OperandTypeException;

class JUMPIFNEQ extends Instruction{

    public function __construct(int $order) {
        $this->opcode = "JUMPIFNEQ";
        $this->operandsCount = 3;
        $this->expectedOperands = [
            ['operandType' => ['label'], 'dataType' => ['any']],
            ['operandType' => ['var', 'const'], 'dataType' => ['any']],
            ['operandType' => ['var', 'const'], 'dataType' => ['any']]
        ];
        parent::__construct($order);
    }

    public function execute(): void{
        $operands = $this->OperandsProcessing();

        $FlowManag = FlowManagement::getInstance();
        
        if($operands[1]->dataType == $operands[2]->dataType || $operands[1]->dataType == "nil" || $operands[2]->dataType == "nil"){
            if($operands[1]->value != $operands[2]->value){
                $FlowManag->jumpToLabel($this->operands[0]);
            }
        } else{
            throw new OperandTypeException("Operands are not the same type");
        }


    }

}

?>