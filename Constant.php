<?php

namespace IPP\Student;

use IPP\Student\Operand;

class Constant extends operand{

    public function __construct(string $operandType, mixed $value, string $dataType) {
        parent::__construct($operandType);
        $this->setOperandDataType($dataType);
        $this->setOperandValue($value);
    }
}

?>