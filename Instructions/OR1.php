<?php
namespace IPP\Student\Instructions;

use IPP\Student\Instruction;
use IPP\Student\Constant;
use IPP\Student\VariableManagement;

class OR1 extends Instruction{

    public function __construct(int $order) {
        $this->opcode = "OR";
        $this->operandsCount = 3;
        $this->expectedOperands = [
            ['operandType' => ['var'], 'dataType' => ['any']],
            ['operandType' => ['var', 'const'], 'dataType' => ['bool']],
            ['operandType' => ['var', 'const'], 'dataType' => ['bool']]
        ];
        parent::__construct($order);
    }

    public function execute(): void{
        $operands = $this->OperandsProcessing();
      
        $VariableManagement = VariableManagement::getInstance();

        if ($operands[1]->value == true || $operands[2]->value == true){
            $res = true;
        } else{
            $res = false;
        }

        $VariableManagement->setValue($operands[0], new Constant('const', $res, 'bool'));

        
    }

}

?>