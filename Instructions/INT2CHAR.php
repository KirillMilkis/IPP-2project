<?php
namespace IPP\Student\Instructions;

use IPP\Student\Instruction;
use IPP\Student\Constant;
use IPP\Student\VariableManagement;
use IPP\Student\Exception\StringOperationException;

class INT2CHAR extends Instruction{

    public function __construct(int $order) {
        $this->opcode = "INT2CHAR";
        $this->operandsCount = 2;
        $this->expectedOperands = [
            ['operandType' => ['var'], 'dataType' => ['any']],
            ['operandType' => ['var', 'const'], 'dataType' => ['int']],
        ];
        parent::__construct($order);
    }

    public function execute(): void{
        $operands = $this->OperandsProcessing();
        $VariableManagement = VariableManagement::getInstance();

        $res = mb_chr($operands[1]->value);
        if($res == false){
            throw new StringOperationException("Error: Invalid Unicode code");
        }

        $VariableManagement->setValue($operands[0], new Constant('const', $res, 'string'));

        
    }

}

?>