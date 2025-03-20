<?php
namespace IPP\Student\Instructions;

use IPP\Student\Instruction;
use IPP\Student\FlowManagement;

class LABEL extends Instruction{

    public function __construct(int $order) {
        $this->opcode = "LABEL";
        $this->operandsCount = 1;
        $this->expectedOperands = [
            ['operandType' => ['label'], 'dataType' => ['null']]
        ];
        parent::__construct($order);
    }

    public function execute(): void{
        $operands = $this->OperandsProcessing();

        $FlowManag = FlowManagement::getInstance();

        $FlowManag->defineLabel($operands[0], $this->order);


    }

}

?>