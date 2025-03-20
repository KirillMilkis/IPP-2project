<?php
namespace IPP\Student\Instructions;

use IPP\Student\Instruction;

class BREAK1 extends Instruction{

    public function __construct(int $order) {
        $this->opcode = "BREAK";
        $this->operandsCount = 0;
        $this->expectedOperands = [
        ];
        parent::__construct($order);
    }

    public function execute(): void{
        // Didnt implement instruction activity. Just for testing purposes.
        ;
    }

}

?>