<?php
namespace IPP\Student\Instructions;

use IPP\Student\Instruction;
use IPP\Student\Constant;
use IPP\Student\Exception\StringOperationException;
use IPP\Student\VariableManagement;

class SETCHAR extends Instruction{

    public function __construct(int $order) {
        $this->opcode = "GETCHAR";
        $this->operandsCount = 3;
        $this->expectedOperands = [
            ['operandType' => ['var'], 'dataType' => ['any']],
            ['operandType' => ['var', 'const'], 'dataType' => ['int']],
            ['operandType' => ['var', 'const'], 'dataType' => ['string']],
        ];
        parent::__construct($order);
    }

    public function execute(): void{
        $operands = $this->OperandsProcessing();

        $VariableManagement = VariableManagement::getInstance();

        $value0 = $VariableManagement->getValue($operands[0]->name, $operands[0]->frame);

        $value1 = $operands[1]->value;
        $value2 = $operands[2]->value;

        if($value2 ==""){
            throw new StringOperationException("ERROR: Empty string");
        }

        if($value1 < 0 || $value1 >= strlen($value0)){
            throw new StringOperationException("ERROR: Index out of bounds");
        }

        $res= substr_replace($value0, $value2[0], $value1, 1);

        $VariableManagement->setValue($operands[0], new Constant('const', $res, 'string'));


    }

}

?>