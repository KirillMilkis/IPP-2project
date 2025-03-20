<?php
namespace IPP\Student\Instructions;

use IPP\Student\Instruction;
use IPP\Student\Constant;
use IPP\Student\VariableManagement;

class LT extends Instruction{

    public function __construct(int $order) {
        $this->opcode = "LT";
        $this->operandsCount = 3;
        $this->expectedOperands = [
            ['operandType' => ['var'], 'dataType' => ['any']],
            ['operandType' => ['var', 'const'], 'dataType' => ['int', 'bool', 'string']],
            ['operandType' => ['var', 'const'], 'dataType' => ['int', 'bool', 'string']],
        ];
        parent::__construct($order);
    }

    public function execute(): void{
        $operands = $this->OperandsProcessing();
       
        $VariableManagement = VariableManagement::getInstance();

        list ($value1, $value2) = Instruction::checkLogicalOperands(array_slice($operands, 1), false);

        $res = $value1 < $value2 ? true : false;

        $VariableManagement->setValue($operands[0], new Constant('const', $res, 'bool'));

        
    }

}

?>