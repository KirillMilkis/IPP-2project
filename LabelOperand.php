<?php

namespace IPP\Student;

class LabelOperand extends Operand{

    public function __construct(string $name) {
        parent::__construct("label");
        $this->setOperandName($name);
    }

}
?>