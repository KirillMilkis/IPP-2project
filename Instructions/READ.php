<?php
namespace IPP\Student\Instructions;

use IPP\Student\Instruction;
use IPP\Student\VariableManagement;
use IPP\Core\FileInputReader;
use IPP\Student\Constant;
use IPP\Student\Exception\SourceStructureException;

class READ extends Instruction{

    public function __construct(int $order) {
        $this->opcode = "READ";
        $this->operandsCount = 2;
        $this->expectedOperands = [
            ['operandType' => ['var', 'const'], 'dataType' => ['any']],
            ['operandType' => ['type'], 'dataType' => ['null']]
        ];
        parent::__construct($order);
    }

    public function execute(FileInputReader $reader): void {
        $operands = $this->OperandsProcessing();
        
        $VariableManagement = VariableManagement::getInstance();


        switch($this->operands[1]->value){
            case 'int':
                $readedValue = $reader->readInt();
                if($readedValue == null){
                    break;
                }
                $VariableManagement->setValue($operands[0], new Constant('const', $readedValue, 'int'));
                return;
            case 'bool':
                $readedValue = $reader->readBool();
                if($readedValue == null){
                    break;
                }
                $VariableManagement->setValue($operands[0], new Constant('const', $readedValue, 'bool'));
                return;
            case 'string':
                $readedValue = $reader->readString();
                if($readedValue == null){
                    break;
                }
                $VariableManagement->setValue($operands[0], new Constant('const', $readedValue, 'string'));
                return;
            default:
                throw new SourceStructureException("Error: Invalid type in READ");
        }

        $VariableManagement->setValue($operands[0], new Constant('const', null, 'nil'));

        
    }

}

?>