<?php
namespace IPP\Student\Instructions;

use IPP\Student\Instruction;
use IPP\Student\Constant;
use IPP\Student\VariableManagement;

class STRLEN extends Instruction{

    public function __construct(int $order) {
        $this->opcode = "STRLEN";
        $this->operandsCount = 2;
        $this->expectedOperands = [
            ['operandType' => ['var'], 'dataType' => ['any']],
            ['operandType' => ['var', 'const'], 'dataType' => ['string']],
        ];
        parent::__construct($order);
    }

    public function execute(): void{
        $operands = $this->OperandsProcessing();

        $VariableManagement = VariableManagement::getInstance();

        if($operands[1]->operandType == "var"){
            $value1 = $VariableManagement->getValue($operands[1]->name, $operands[1]->frame);
        } else{
            $value1 = $operands[1]->value;
        }

        $res = strlen($value1);

        $VariableManagement->setValue($operands[0], new Constant('const', $res, 'int'));


    }

}

?>