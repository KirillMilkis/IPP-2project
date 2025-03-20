<?php

namespace IPP\Student;

use IPP\Core\Exception\InternalErrorException;
use IPP\Student\Exception\FrameAccessException;

class Frames {

    /** @var mixed*/
    private static $instance = null;
    /** @var array<int, array<string, Variable>>*/
    protected $frameStack;
    /** @var array<string, Variable>|null*/
    protected $tempFrame;
    /** @var array<string, Variable> */
    protected $globalFrame;

    protected int $stackIndex;
    protected int $limit;

    public function __construct() {
        $this->globalFrame = array();
        $this->frameStack = array();
        $this->tempFrame = null;
        $this->stackIndex = -1;
        $this->limit = 100;
    }

    // Singletone feature getInstance.
    public static function getInstance(): Frames {
        if (self::$instance == null) {
            self::$instance = new Frames();
        }
        return self::$instance;
    }

    // Method that creates new temporary frame for variables.
    public function createFrame(): void{
        $this->tempFrame = array();
    }

    // Method that pushes temporary frame to the stack of frames.
    public function pushFrame(): void{
        if ($this->stackIndex <= $this->limit) {
            if ($this->tempFrame !== null) {
                // We need to change attribute actual frame info in variable objects in processed frame.
                foreach($this->tempFrame as $variable){
                    $variable->frame = "LF";
                }
                $this->stackIndex++;
                $this->frameStack[$this->stackIndex] = $this->tempFrame;
                // Temporart frame from this moment does not exists.
                $this->tempFrame = null;
            } else{
                throw new FrameAccessException("Error: Temporary frame does not exists, nothing to push");
            }
        } else {
            throw new InternalErrorException("Error: Frames stack overflow");
        }
    }

    // Method that pops frame and assign it to temporary frame. One last local frame is removed from stack.
    public function popFrame(): void{
        if ($this->stackIndex > -1) {
            $this->tempFrame = $this->frameStack[$this->stackIndex];
            unset($this->frameStack[$this->stackIndex]);
            // We need to change attribute actual frame info in variable objects in processed frame.
            foreach($this->tempFrame as $variable){
                $variable->frame = "TF";
            }
            $this->stackIndex--;
        } else {
            throw new FrameAccessException("Error: Frames stack is empty");
        }
        
    }

    // Method returns actual frame, if it doesnt exists, return null.
    /**
     * @return array<string, Variable>|null
     */
    public function &getActualFrame(): mixed{
        if ($this->tempFrame != null) {
            return $this->tempFrame;
        } else if ($this->stackIndex > -1){
            return $this->frameStack[$this->stackIndex];
        } else{
            return null;
        }
    }

    /**
     * @return array<string, Variable>
     */
    public function &getLocalFrame(): array{
        if ($this->stackIndex > -1) {
            return $this->frameStack[$this->stackIndex];
        } else {
            throw new FrameAccessException("Error: Frames stack is empty");
        }
    }

    /**
     * @return array<string, Variable>
     */
    public function &getTempFrame(): array{
        if ($this->tempFrame !== null) {
            return $this->tempFrame;
        } else {
            throw new FrameAccessException("Error: Temporary frame does not exists");
        }
    }

    /**
     * @return array<string, Variable>
     */
    public function &getGlobalFrame(): array{
        return $this->globalFrame;
    }

    // Before getting local frame it is better to check if it exists.
    public function isFrameStackEmpty(): bool{
        if($this->stackIndex == -1){
            return true;
        } else {
            return false;
        }
    }


}

?>