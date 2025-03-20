<?php
namespace IPP\Student\Instructions;

use IPP\Student\Instruction;
use IPP\Student\Frames;

class POPFRAME extends Instruction{

    public function __construct(int $order) {
        $this->opcode = "POPFRAME";
        $this->operandsCount = 0;
        $this->expectedOperands = [];
        parent::__construct($order);
    }
    
    public function execute(): void{
        $operands = $this->OperandsProcessing();

        $frames = Frames::getInstance();
        $frames->popFrame();
        
    }


}

?>