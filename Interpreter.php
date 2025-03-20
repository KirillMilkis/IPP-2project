<?php

namespace IPP\Student;

use IPP\Core\AbstractInterpreter;
use IPP\Student\InstructionFactory;
use IPP\Student\Variable;
use DOMElement;
use IPP\Student\Exception\SourceStructureException;

class Interpreter extends AbstractInterpreter
{
     /** @var array<int, object> */
    private static $allInstructions = array();
     /** @var array<int, Variable|Constant> */
    public static $dataStack = array();
    /** @var int */
    public static $dataStackLimit = 150;

    private function parseInstr(): int{

        // Flow management init to control actual order and jump to next order.
        $flowManag = FlowManagement::getInstance();
        if(!$flowManag->isNextOrder()){
            return 0;
        }
        $currentInstrOrder = $flowManag->getNextOrder();
        while(true){
            // Key in allInstructions array is the order of the instruction.
            $currentInstruction = self::$allInstructions[$currentInstrOrder];

            // In different instruction cases, there are different parameters to pass to execute function.
            // In case Read we pass input stream, in case Write we pass output stream.
            if($currentInstruction->opcode == "READ"){
                $currentInstruction->execute($this->input);
            } else if($currentInstruction->opcode == "WRITE"){
                $currentInstruction->execute($this->stdout);
            // In case of LABEL we do nothing, just pass to next instruction.
            } else if($currentInstruction->opcode == "LABEL"){
                ;
            // Exit return interpreter from this function and then end execution
            } else if($currentInstruction->opcode == "EXIT"){
                return $currentInstruction->execute();
            }
            else{
                $currentInstruction->execute();
            }
            

            // Take next order from flow management if exists.
            // Phpstan show error here(condition always return true), but it is not true error, i tested.
            // I dont know how to fix that.
            // @phpstan-ignore-next-line
            if($flowManag->isNextOrder() == true){
                $currentInstrOrder = $flowManag->getNextOrder();
            } else{
                break;
            }
            
        }
        // @phpstan-ignore-next-line
        return 0;

    }

    /**
     * @param \DOMNodeList<\DOMElement> $instructions
     * @return void
     */
    private function loadInstrIntoArray(\DOMNodeList $instructions): void{
        // Flow management init to pass on this class all labels and orders
        $flowManag = FlowManagement::getInstance();
        // Factory to choose which instruction to create.
        foreach($instructions as $instructionNode){
            if(!$instructionNode instanceof DOMElement){
                continue;
            }
            // Check if the only instructions we have among program child nodes.
            if($instructionNode->tagName != "instruction"){
                throw new SourceStructureException("Error: Instruction error");
            }
            // Get instruction order.
            $order = $instructionNode->getAttribute('order');
            $trimmedOrder= trim($order);
            $order = intval($trimmedOrder);
            // Order must be positive integer.
            if($order < 1){
                throw new SourceStructureException("Error: Order error");
            }
            // Order must be unique and should increase with each instruction.
            if(self::$allInstructions != []){
                $largestKey = max(array_keys(self::$allInstructions));
                if($order <= $largestKey){
                    throw new SourceStructureException("Error: Order error");
                }
            }
            
            // Cal instruction factory to create instruction object.
            $instruction = InstructionFactory::createInstruction($instructionNode->getAttribute('opcode'), $order);
            
            $args_tags = array();
            foreach ($instructionNode->childNodes as $operand) { 
                // Then process only child that have tag argX.     
                if ($operand instanceof DOMElement) {
                    // If there is argX duplicates, throw error.
                    if(in_array($operand->tagName, $args_tags)){
                        throw new SourceStructureException("Error: Argument error");
                    }
                    $instruction->addOperands($operand, intval(str_replace('arg', '', $operand->tagName)));
                    // Remember the tag name to check duplicates.
                    $args_tags[] = $operand->tagName;
                }
            }

            // if instruction is LABEL, execute it to define label in flow management.
            if($instruction->opcode == "LABEL"){
                $instruction->execute();
            } 

            self::$allInstructions[$order] = $instruction;
            // Call flow management to set order we read into local array in class which we will use insight this class.
            $flowManag->setOrderListElem($order);
        }
        
    }



    public function execute(): int{
        // Getting XML structure into a variable dom.
        $dom = $this->source->getDOMDocument();
        // Getting the root element of the XML structure and check if the program is empty.
        $languageNode = $dom->getElementsByTagName('program');
        if($languageNode == null || $languageNode->length != 1){
            throw new SourceStructureException("Error: Missing language attribute");
        }
        // Getting the language attribute of the root element and check if it has attribute IPPcode24.
        $languageNode = $languageNode->item(0);
        if ($languageNode instanceof \DOMElement) {
            if ($languageNode->getAttribute('language') != "IPPcode24") {
                throw new SourceStructureException("Error: Wrong language");
            }
        }else{
            throw new SourceStructureException("Error: Wrong language");
        }
       
        // Getting the program child nodes (must be only instructions) from the XML structure.
        $instructions = $languageNode->childNodes;
        // Loading the instructions into a special array from which interpreter will take and execute instr.
        $this->loadInstrIntoArray($instructions);
        // Call main function to iterate and execute every instruction.
        return $this->parseInstr();
    }

    
}

?>