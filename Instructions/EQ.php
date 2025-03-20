<?php
namespace IPP\Student\Instructions;

use IPP\Student\Instruction;
use IPP\Student\Constant;
use IPP\Student\VariableManagement;

class EQ extends Instruction{

    public function __construct(int $order) {
        $this->opcode = "EQ";
        $this->operandsCount = 3;
        $this->expectedOperands = [
            ['operandType' => ['var'], 'dataType' => ['any']],
            ['operandType' => ['var', 'const'], 'dataType' => ['any']],
            ['operandType' => ['var', 'const'], 'dataType' => ['any']],
        ];
        parent::__construct($order);
    }

    public function execute(): void{
        $operands = $this->OperandsProcessing();
        
        $VariableManagement = VariableManagement::getInstance();

        list($value1, $value2) = Instruction::checkLogicalOperands(array_slice($operands, 1), true);

        $res = $value1 == $value2 ? true : false;

        $VariableManagement->setValue($operands[0], new Constant('const', $res, 'bool'));

        
    }

}

?>