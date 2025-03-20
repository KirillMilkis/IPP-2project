<?php

namespace IPP\Student\Exception;

use IPP\Core\ReturnCode;
use IPP\Core\Exception\IPPException;
use Throwable;

/**
 * Exception for frame access errors
 */
class FrameAccessException extends IPPException
{
    public function __construct(string $message = "Frame Access Error", ?Throwable $previous = null)
    {
        parent::__construct($message, ReturnCode::FRAME_ACCESS_ERROR, $previous);
    }
}
