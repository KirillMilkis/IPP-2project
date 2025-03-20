<?php
namespace IPP\Student\Instructions;

use IPP\Student\Instruction;
use IPP\Student\Constant;
use IPP\Student\VariableManagement;

class NOT extends Instruction{

    public function __construct(int $order) {
        $this->opcode = "NOT";
        $this->operandsCount = 2;
        $this->expectedOperands = [
            ['operandType' => ['var'], 'dataType' => ['any']],
            ['operandType' => ['var', 'const'], 'dataType' => ['bool']],
        ];
        parent::__construct($order);
    }

    public function execute(): void{
        $operands = $this->OperandsProcessing();
        $VariableManagement = VariableManagement::getInstance();

        $value = $operands[1]->value;
        $res = !$value;

        $VariableManagement->setValue($operands[0], new Constant('const', $res, 'bool'));
        
    }

}

?>