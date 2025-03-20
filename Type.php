<?php

namespace IPP\Student;

class Type extends Operand{

    public function __construct(mixed $value) {
        parent::__construct("type");
        $this->setOperandValue($value);
    }

}

?>