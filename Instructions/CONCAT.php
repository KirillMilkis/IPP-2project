<?php
namespace IPP\Student\Instructions;

use IPP\Student\Instruction;
use IPP\Student\Constant;
use IPP\Student\VariableManagement;

class CONCAT extends Instruction{

    public function __construct(int $order) {
        $this->opcode = "CONCAT";
        $this->operandsCount = 3;
        $this->expectedOperands = [
            ['operandType' => ['var'], 'dataType' => ['any']],
            ['operandType' => ['var', 'const'], 'dataType' => ['string']],
            ['operandType' => ['var', 'const'], 'dataType' => ['string']]
        ];
        parent::__construct($order);
    }

    public function execute(): void{
        $operands = $this->OperandsProcessing();
        $VariableManagement = VariableManagement::getInstance();

        $value1 = $operands[1]->value;
        $value2 = $operands[2]->value;

        $res = $value1 . $value2;

        $VariableManagement->setValue($operands[0], new Constant('const', $res, 'string'));


    }

}

?>