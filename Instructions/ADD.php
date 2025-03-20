<?php
namespace IPP\Student\Instructions;

use IPP\Student\Instruction;
use IPP\Student\Constant;
use IPP\Student\VariableManagement;

class ADD extends Instruction{
   

    public function __construct(int $order) {
        $this->opcode = "ADD";
        $this->operandsCount = 3;
        $this->expectedOperands = [
            ['operandType' => ['var'], 'dataType' => ['any']],
            ['operandType' => ['var', 'const'], 'dataType' => ['int']],
            ['operandType' => ['var', 'const'], 'dataType' => ['int']]
        ];
        parent::__construct($order);
    }

    public function execute(): void{
        $operands = $this->OperandsProcessing();
        
        $VariableManagement = VariableManagement::getInstance();

        list($value1, $value2) = Instruction::checkArithmeticOperands(array_slice($operands, 1));
        
        $result = $value1 + $value2;

        $VariableManagement->setValue($operands[0], new Constant('const', $result, 'int'));

    }

}

?>