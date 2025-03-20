<?php

namespace IPP\Student;

use IPP\Student\Enum\OperandType;
use IPP\Student\Operand;
use IPP\Student\Frame;
use IPP\Student\VariableManagement;

class Variable extends operand{
    public string $frame;

    public function __construct(string $operandType, string $name, string $frame) {
        parent::__construct($operandType);
        //Only Variable has frame.
        $this->frame = $frame;
        $this->setOperandName($name);
    }


}

?>
