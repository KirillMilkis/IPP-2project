<?php
namespace IPP\Student\Instructions;
use IPP\Student\Instruction;
use IPP\Student\VariableManagement;
use IPP\Student\Exception\StringOperationException;
use IPP\Student\Constant;

class STRI2INT extends Instruction{
    public function __construct(int $order) {
        $this->opcode = "STRI2INT";
        $this->operandsCount = 3;
        $this->expectedOperands = [
            ['operandType' => ['var'], 'dataType' => ['any']],
            ['operandType' => ['var', 'const'], 'dataType' => ['string']],
            ['operandType' => ['var', 'const'], 'dataType' => ['int']],
        ];
        parent::__construct($order);
    }

    public function execute(): void{
        $operands = $this->OperandsProcessing();

        $VariableManagement = VariableManagement::getInstance();

        $strLen = strlen($operands[1]->value);
        $index = $operands[2]->value;

        if($index > $strLen - 1 || $index < 0){
            throw new StringOperationException("Error: Invalid index");
        }

        $res = ord($operands[1]->value[$index]);

        $VariableManagement->setValue($operands[0], new Constant('const', $res, 'int'));

    }
}

?>