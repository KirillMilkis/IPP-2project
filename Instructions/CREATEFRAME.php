<?php
namespace IPP\Student\Instructions;

use IPP\Student\Instruction;
use IPP\Student\Frames;

class CREATEFRAME extends Instruction{

    public function __construct(int $order) {
        $this->opcode = "CREATEFRAME";
        $this->operandsCount = 0;
        $this->expectedOperands = [];
        parent::__construct($order);
    }
    
    public function execute(): void{
        $operands = $this->OperandsProcessing();

        $frames = Frames::getInstance();
        $frames->createFrame();
        
    }


}

?>