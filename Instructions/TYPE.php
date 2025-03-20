<?php
namespace IPP\Student\Instructions;

use IPP\Student\Instruction;
use IPP\Student\VariableManagement;
use IPP\Student\Constant;
use IPP\Student\Exception\VariableAccessException;


class TYPE extends Instruction{

    public function __construct(int $order) {
        $this->opcode = "TYPE";
        $this->operandsCount = 2;
        $this->expectedOperands = [
            ['operandType' => ['var'], 'dataType' => ['any']],
            ['operandType' => ['var','const'], 'dataType' => ['any']]
        ];
        parent::__construct($order);
    }

    public function execute(): void{
        $operands = array_map(function($object) {
            return clone $object;
        }, $this->operands);
        $VariableManagement = VariableManagement::getInstance();
        if(!$VariableManagement->checkVariableDefinition($operands[0]->name, $operands[0]->frame)) {
            throw new VariableAccessException("Error: Variable not defined");
        }
        $operands[0] = &$VariableManagement->getVariable($operands[0]->name, $operands[0]->frame);

        if($operands[1]->operandType == "var"){
            if($VariableManagement->checkVariableDefinition($operands[1]->name, $operands[1]->frame)) {
                $operands[1] = $VariableManagement->getVariable($operands[1]->name, $operands[1]->frame);
            }
        }

        $this->checkOperands($operands);

        $type = $operands[1]->dataType;
        if ($type == null){
            $type = "";
        };

        $VariableManagement->setValue($operands[0], new Constant('const', $type, 'string'));


    }

}

?>