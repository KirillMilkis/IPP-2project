<?php
namespace IPP\Student\Instructions;

use IPP\Student\Instruction;
use IPP\Student\FlowManagement;

class RETURN1 extends Instruction{

    public function __construct(int $order) {
        $this->opcode = "RETURN";
        $this->operandsCount = 0;
        $this->expectedOperands =[];
        parent::__construct($order);
    }

    public function execute(): void{
        $operands = $this->OperandsProcessing();

        $FlowManag = FlowManagement::getInstance();

        $FlowManag->returnFromLabel();

    }

}

?>