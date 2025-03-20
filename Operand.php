<?php

namespace IPP\Student;

use IPP\Student\Exception\OperandValueException;
use IPP\Student\Exception\SourceStructureException;

class Operand {
    /* @var string|int|bool */
    public mixed $value;
    public string $operandType;
    public ?string $dataType;
    public ?string $name;

    public function __construct(string $operandType) {
        $this->operandType = $operandType;
        $this->value = null;
        $this->dataType = null;
        $this->name = null;
    }

    protected function setOperandName(string $name): void{
        $this->name = $name;
    }

    protected function setOperandValue(mixed $value): void{
        $this->value = $value;
    }

    protected function setOperandDataType(string $dataType): void{
        $this->dataType = $dataType;
    }


    /**
     * Static Method which works like a factory for creating different types of operands.
     * @return Variable|Constant|LabelOperand|Type
     */
    public static function operandBuild(string $type, string $value){
        switch($type) {
            case "label":
                $operandType = "label";
                $name = $value;
                $newOperand = new LabelOperand($name);
                return $newOperand; 
            case "type":
                $typeValue = $value;
                if($typeValue != "int" && $typeValue != "bool" && $typeValue != "string"){
                    throw new SourceStructureException("Error: Unknown type");
                }
                $newOperand = new Type($typeValue);
                return $newOperand;
            case "var":
                $operandType = "var";
                // Variable has format like XX@YY where XX is frame and YY is name.
                $parts = explode('@', $value);
                $frame = trim($parts[0]);
                if($frame != "GF" && $frame != "LF" && $frame != "TF"){
                    throw new SourceStructureException("Error: Unknown frame");
                } 
                $name = trim($parts[1]);
                $newOperand = new Variable("var", $name, $frame);
                return $newOperand;
            // In case of constant we accept differernt types in $value variable for comfort usage in future.
            case "float":
                if (!is_numeric($value)) {
                    throw new OperandValueException("Error: Conversion error: value is not float");
                }
                if((float) $value == $value){
                    $value = (float)$value;
                    $constantType = "float";
                    $newOperand = new Constant("const", $value, $constantType);
                    return $newOperand;
                } else{
                    throw new OperandValueException("Error: Conversion error: value is not float");
                }          
            case "int":
                if (!is_numeric($value)) {
                    throw new OperandValueException("Error: Conversion error: value is not integer");
                }
                if ((int) $value == $value){
                    $value = (int)$value;
                    $constantType = "int";
                    $newOperand = new Constant("const", $value, $constantType);
                    return $newOperand;
                } else {
                    throw new OperandValueException("Error: Conversion error: value is not integer");
                }     
            case "bool":
                if (preg_match('/^(true|false)$/i', $value)) {
                    $value = strtolower($value) == 'true' ? true : false;
                    $constantType = "bool";
                    $newOperand = new Constant("const", $value, $constantType);
                    return $newOperand;
                } else {
                    throw new OperandValueException("Error: Conversion error: value is not boolean");
                }
            case "string":
                if (preg_match('/^.*$/s', $value)) {
                    $value = str_replace("\n", "", $value);
                    // Transform escape sequences to characters.
                    $value = preg_replace_callback('/\\\\(\d{2,3})/', function ($matches) {
                        $matches[1] = intval($matches[1]);
                        return chr($matches[1]);
                    }, $value);
                    $constantType = "string";
                    $newOperand = new Constant("const", $value, $constantType);
                    return $newOperand;
                } else {
                    throw new OperandValueException("Error: Conversion error: value is not string");
                }
            case "nil": 
                if (preg_match('/^nil$/', $value)) {
                    $value = null;
                    $constantType = "nil";
                    $newOperand = new Constant("const", $value, $constantType);
                    return $newOperand;
                } else {
                    throw new OperandValueException("Error: Conversion error: value is not nil");
                }
            default:
                throw new SourceStructureException("Error: Unknown type");
        }   
       
    }
}
?>