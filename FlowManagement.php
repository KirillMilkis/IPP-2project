<?php

namespace IPP\Student;

use IPP\Core\Exception\InternalErrorException;
use IPP\Student\Exception\SemanticException;
use IPP\Student\Exception\ValueException;

class FlowManagement {

    /** @var null|FlowManagement */
    private static $instance = null;

    /** @var array<int, int> */
    private array $labelsStorage;
    /** @var array<int, array<int, int|string>> */
    private array $callStack;
    private int $callStackIndex;
    private int $callStackLimit;
    private int $currentOrder;
    /** @var  array<int, int>*/
    private array $orderList;
    private int $orderListIndex;

    public function __construct(){
        $this->labelsStorage = array();
        $this->callStack = array();
        $this->callStackIndex = -1;
        $this->callStackLimit = 100;
        $this->currentOrder = 0;
        $this->orderList = array();
        $this->orderListIndex = -1;
    }

    // Singletone feature getInstance.
    public static function getInstance(): FlowManagement{
        if (self::$instance == null) {
            self::$instance = new FlowManagement();
        }
        return self::$instance;
    }

    // Method checks if label is already defined and if not, it puts it into labelStorage array.
    public function defineLabel(LabelOperand $label,int $order): void{
        if(array_key_exists($label->name, $this->labelsStorage)){
            throw new SemanticException("Error: Label already defined"); 
        }
        $this->labelsStorage[$label->name] = $order;
    }

    // Method helps to CALL instruction to change actual instruction order.
    public function callLabel(LabelOperand $label): void{
        // Check if call stack is not full.
        if($this->callStackIndex >= $this->callStackLimit){
            throw new InternalErrorException("Error: Call stack overflow");
        }
        // Check if label is not defined before.
        if(!array_key_exists($label->name, $this->labelsStorage)){
            throw new SemanticException("Error: Label not defined"); 
        }
        $this->callStackIndex++;
        // Call stack contain two elements array with label name and order number where we called this label.
        $this->callStack[$this->callStackIndex] = array($label->name, $this->currentOrder);
        $this->currentOrder = $this->labelsStorage[$label->name];
        // Find index of current order in instruction order list.
        $index = array_search($this->currentOrder, $this->orderList);
        if ($index === false) {
            throw new InternalErrorException("Error: Current order not found in order list");
        }
        $this->orderListIndex = (int)$index;
    }

    // Method helps to JUMP, JUMPIFEQ, JUMPIFNEQ instruction to change actual instruction order.
    public function jumpToLabel(LabelOperand $label): void{
        if(!array_key_exists($label->name, $this->labelsStorage)){
            throw new SemanticException("Error: Label not defined"); 
        }
        // Search for order number of label we want.
        $this->currentOrder = $this->labelsStorage[$label->name];
        $index = array_search($this->currentOrder, $this->orderList);
        if ($index === false) {
            throw new InternalErrorException("Error: Current order not found in order list");
        }
        $this->orderListIndex = (int)$index;
    }

    // Method helps to RETURN instruction (after instruction CALL) to change actual instruction order.
    public function returnFromLabel(): void{
        if($this->callStackIndex > -1){
            // Taking order number from where we called label in function CALL.
            $this->currentOrder = $this->callStack[$this->callStackIndex][1];
            $index = array_search($this->currentOrder, $this->orderList);
            if ($index === false) {
                throw new InternalErrorException("Error: Current order not found in order list");
            }
            // Actualize order list index and pop callStack.
            $this->orderListIndex = (int)$index;
            unset($this->callStack[$this->callStackIndex]);
            $this->callStackIndex--;
        } else{
            throw new ValueException("Error: Call stack is empty");
        }
    }

    // Method is called from Interpreter class to create local array of order numbers.
    public function setOrderListElem(int $orderNum): void{
        $index = count($this->orderList);
        $this->orderList[$index] = $orderNum;

    }

    // Method that indicates whether the interpreter has reached the end or not.
    public function isNextOrder(): bool{
        return isset($this->orderList[$this->orderListIndex + 1]);
    }

    public function getNextOrder(): int{
        $this->orderListIndex++;
        $this->currentOrder = $this->orderList[$this->orderListIndex];
        return $this->orderList[$this->orderListIndex];
        
    }

    public function getFirstOrder(): int{
        if(isset($this->orderList[0])){
            $this->currentOrder = $this->orderList[0];
            return $this->orderList[0];
        } else{
            throw new InternalErrorException("Error: Order list is empty");
        }
    }

    public function getCurrentOrder(): int{
        return $this->currentOrder;
    }

    // Method that only return it own argument, but it is relevant to keep it in this class.
    public function exitProgram(int $returnCode): int{
        return $returnCode;
    }


}

?>