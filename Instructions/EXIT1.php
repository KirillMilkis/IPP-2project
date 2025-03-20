<?php
namespace IPP\Student\Instructions;

use IPP\Student\Instruction;
use IPP\Student\VariableManagement;
use IPP\Student\Exception\OperandValueException;
use IPP\Student\FlowManagement;

class EXIT1 extends Instruction{

    public function __construct(int $order) {
        $this->opcode = "EXIT";
        $this->operandsCount = 1;
        $this->expectedOperands = [
            ['operandType' => ['var','const'], 'dataType' => ['int']],
        ];
        parent::__construct($order);
    }

    public function execute(): int{
        $operands = $this->OperandsProcessing();

        $FlowManag = FlowManagement::getInstance();

        $returnCode = $operands[0]->value;
        // Return code from this function to end the interpret program.
        if($returnCode >= 0 && $returnCode <= 9){
            return $FlowManag->exitProgram($returnCode);
        } else{
            throw new OperandValueException("Return code is not in range 0-8");
        }

    }

}

?>