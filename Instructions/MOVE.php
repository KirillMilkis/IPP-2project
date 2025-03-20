<?php
namespace IPP\Student\Instructions;

use IPP\Student\Instruction;
use IPP\Student\VariableManagement;

class MOVE extends Instruction{

    public function __construct(int $order) {
        $this->opcode = "MOVE";
        $this->operandsCount = 2;
        $this->expectedOperands = [
            ['operandType' => ['var'], 'dataType' => ['any']],
            ['operandType' => ['var', 'const'], 'dataType' => ['any']]
        ];
        parent::__construct($order);
    }

    public function execute(): void{
        $operands = $this->OperandsProcessing();
        $VariableManagement = VariableManagement::getInstance();


        $VariableManagement->setValue($operands[0], $operands[1]);
    }

}

?>