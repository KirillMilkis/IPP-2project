<?php
namespace IPP\Student\Instructions;

use IPP\Student\Instruction;
use IPP\Student\FlowManagement;

class JUMP extends Instruction{

    public function __construct(int $order) {
        $this->opcode = "JUMP";
        $this->operandsCount = 1;
        $this->expectedOperands = [
            ['operandType' => ['label'], 'dataType' => ['any']]
        ];
        parent::__construct($order);
    }

    public function execute(): void{
        $operands = $this->OperandsProcessing();

        $FlowManag = FlowManagement::getInstance();

        $FlowManag->jumpToLabel($operands[0]);


    }

}

?>