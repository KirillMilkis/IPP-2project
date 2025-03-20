<?php
namespace IPP\Student\Instructions;

use IPP\Student\Instruction;

class DPRINT extends Instruction{

    public function __construct(int $order) {
        $this->opcode = "DPRINT";
        $this->operandsCount = 1;
        $this->expectedOperands = [
            ['operandType' => ['var', 'const'], 'dataType' => ['bool']],
        ];
        parent::__construct($order);
    }

    public function execute(): void{
        // Didnt implement instruction activity. Just for testing purposes.
        ;
    }

}

?>