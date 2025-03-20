<?php
namespace IPP\Student\Instructions;

use IPP\Student\Instruction;
use IPP\Student\VariableManagement;
use IPP\Core\StreamWriter;
use IPP\Student\Exception\OperandTypeException;

class WRITE extends Instruction{

    public function __construct(int $order) {
        $this->opcode = "WRITE";
        $this->operandsCount = 1;
        $this->expectedOperands = [
            ['operandType' => ['var', 'const'], 'dataType' => ['any']]
        ];
        parent::__construct($order);
    }

    public function execute(StreamWriter $writer): void{
        $operands = $this->OperandsProcessing();
        $VariableManagement = VariableManagement::getInstance();

        $value = null;

        $value = $operands[0]->value;

        switch($operands[0]->dataType){
            case "int":
                $writer->writeInt($value);
                break;
            case "bool":
                $writer->writeBool($value);
                break;
            case "string":
                $writer->writeString($value);
                break;
            case "nil":
                fwrite(STDOUT, "");
                break;
            default:
                throw new OperandTypeException("Error: Invalid data type");
        }

        
    }

}

?>