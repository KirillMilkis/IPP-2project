<?php

namespace IPP\Student;

use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\VariableAccessException;

class VariableManagement {

    /** @var null|VariableManagement */
    private static $instance = null;
    /** @var Frames */
    private $frames;

    public function __construct() {
        $this->frames = Frames::getInstance();
    }

    // Singletone feature getInstance.
    public static function getInstance(): VariableManagement{
        if(self::$instance == null){
            self::$instance = new VariableManagement();
        }
        return self::$instance;
    }

    // Method that choose right frame for variable and puts it into frame's array.
    public function defineVariable(Variable $var): void{
        switch($var->frame){
            case "GF":
                $globalFrame = &$this->frames->getGlobalFrame();
                $globalFrame[$var->name] = $var;
                break;
            case "LF":
                $localFrame = &$this->frames->getLocalFrame();
                $localFrame[$var->name] = $var;
                break;
            case "TF":
                $tempFrame = &$this->frames->getTempFrame();
                $tempFrame[$var->name] = $var;
                break;

        }
    }

    /**
     * Get value of variable from different frames. It is not safely, variable can be null and method will return null.
     *  @return string|int|bool 
     * */
    public function getValue(string $name, string $frame): mixed{
        switch($frame){
            case "GF":
                $globalFrame = $this->frames->getGlobalFrame();
                return $globalFrame[$name]->value;
            case "LF":
                $localFrame = $this->frames->getLocalFrame();
                return $localFrame[$name]->value;
            case "TF":
                $tempFrame = $this->frames->getTempFrame();
                return $tempFrame[$name]->value;
            default:
                throw new VariableAccessException("Error: Unknown frame");
        }
    }

    /**
     * Get variable object from different frames. It is not safely, variable can be not defined and method will return null.
     * It is better to use checkVariableDefinition method before this method.
     *  @return Variable 
     * */
    public function &getVariable(string $name, string $frame): Variable{
        switch($frame){
            case "GF":
                $globalFrame = &$this->frames->getGlobalFrame(); 
                return $globalFrame[$name];
            case "LF":
                $localFrame = &$this->frames->getLocalFrame();
                return $localFrame[$name];
            case "TF":
                $tempFrame = &$this->frames->getTempFrame();
                return $tempFrame[$name];
            default:
                throw new VariableAccessException("Error: Unknown frame");
        }
    }

    /**
     * Check if variable is defined in different frames.
     *  @return bool 
     * */
    public function checkVariableDefinition(string $name, string $frame): bool{
        // Universal function for every frame
        $isExists = function (array $frame, string $name){
            if(!array_key_exists($name, $frame)){
                return false;
            }
            return true;
        };
        switch($frame){
            case "GF":
                $globalFrame = $this->frames->getGlobalFrame();
                return $isExists($globalFrame, $name);
            case "LF":
                $localFrame = $this->frames->getLocalFrame();
                return $isExists($localFrame, $name);
            case "TF":
                $tempFrame = $this->frames->getTempFrame();
                return $isExists($tempFrame, $name);
            default:
                throw new VariableAccessException("Error: Unknown frame");
        }
    }

    /**
     * Set value of variable from different frames. It can append variable or constant like a operand with required value for 1 operand.
     * @param Variable $operandForAssignment
     * @param Variable|Constant $operandForValue
     */
    public function setValue(&$operandForAssignment,$operandForValue): void{
        // First operand must be var.
        if($operandForAssignment->operandType != "var"){
            throw new OperandTypeException("Error: Invalid operand for assignment");
        } 
        // If variable is not defined, we cannot assign value to it.
        if(!$this->checkVariableDefinition($operandForAssignment->name, $operandForAssignment->frame)){
            throw new VariableAccessException("Variable for assignment is not defined");
        }
        // If second object is variable, we must check if it is defined and get its value.
        if ($operandForValue->operandType == "var"){
            if(!$this->checkVariableDefinition($operandForValue->name, $operandForValue->frame)){
                throw new VariableAccessException("Variable for value is not defined");
            }
            $value = $this->getValue($operandForValue->name, $operandForValue->frame);
            $operandForAssignment->value = $value;
            $operandForAssignment->dataType = $operandForValue->dataType;
        // If second object is constant, we can assign its value to first object directly.
        } else if($operandForValue->operandType == "const") {
            $operandForAssignment->value = $operandForValue->value;
            $operandForAssignment->dataType = $operandForValue->dataType;
        } else{
            throw new OperandTypeException("Error: Invalid value for assignment");
        }
    }


    /**
     * Method is called in almost all instructions executions, because if there are vars in operands, they must be found.
     * @param array<Variable|Constant|Type|LabelOperand>  $operands
     */
    public function assignDefinedVariables(array &$operands): void {
        for($i = 0; $i < count($operands); $i++) {
            if($operands[$i]->operandType == 'var') {
                // Check for variable previously definition.
                if(!$this->checkVariableDefinition($operands[$i]->name, $operands[$i]->frame)) {
                    throw new VariableAccessException("Error: Variable not defined");
                }
                $operands[$i] = &$this->getVariable($operands[$i]->name, $operands[$i]->frame);
            }
        }
    }




}

?>