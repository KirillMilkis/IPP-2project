<?php

namespace IPP\Student;

use IPP\Student\Exception\SourceStructureException;
use IPP\Student\Instructions;


class InstructionFactory {
    public function __construct() {
    }
    /**
     * @param string $opcode
     * @param int $order
     * @return object
     */
    public static function createInstruction(string $opcode,int $order): object {
        // Make opcode uppercase and choose the right class to init.
        $opcode = strtoupper($opcode);
        switch($opcode) {
            case "DEFVAR":
                return new Instructions\DEFVAR($order);
            case "MOVE":
                return new Instructions\MOVE($order);
            case "CREATEFRAME":
                return new Instructions\CREATEFRAME($order);
            case "PUSHFRAME":
                return new Instructions\PUSHFRAME($order);
            case "POPFRAME":
                return new Instructions\POPFRAME($order);
            case "PUSHS":
                return new Instructions\PUSHS($order);
            case "POPS":
                return new Instructions\POPS($order);
            case "ADD":
                return new Instructions\ADD($order);
            case "SUB":
                return new Instructions\SUB($order);
            case "MUL":
                return new Instructions\MUL($order);
            case "IDIV":
                return new Instructions\IDIV($order);
            case "LT":
                return new Instructions\LT($order);
            case "GT":
                return new Instructions\GT($order);
            case "EQ":
                return new Instructions\EQ($order);
            case "AND":
                return new Instructions\AND1($order);
            case "OR":
                return new Instructions\OR1($order);
            case "NOT":
                return new Instructions\NOT($order);
            case "READ":
                return new Instructions\READ($order);
            case "WRITE":
                return new Instructions\WRITE($order);
            case "LABEL":
                return new Instructions\LABEL($order);
            case "JUMP":
                return new Instructions\JUMP($order);
            case "JUMPIFEQ":
                return new Instructions\JUMPIFEQ($order);
            case "JUMPIFNEQ":
                return new Instructions\JUMPIFNEQ($order);
            case "EXIT":
                return new Instructions\EXIT1($order);
            case "CALL":
                return new Instructions\CALL($order);
            case "RETURN":
                return new Instructions\RETURN1($order);
            case "CONCAT":
                return new Instructions\CONCAT($order);
            case "STRLEN":
                return new Instructions\STRLEN($order);
            case "GETCHAR":
                return new Instructions\GETCHAR($order);
            case "SETCHAR":
                return new Instructions\SETCHAR($order);
            case "TYPE":
                return new Instructions\TYPE($order);
            case "INT2CHAR":
                return new Instructions\INT2CHAR($order);
            case "STRI2INT":
                return new Instructions\STRI2INT($order);
            case "DPRINT":
                return new Instructions\DPRINT($order);
            case "BREAK":
                return new Instructions\BREAK1($order);
            default:
                throw new SourceStructureException("Unknown opcode: $opcode");
            
            }
        
    }
}

?>