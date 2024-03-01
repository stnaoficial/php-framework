<?php

namespace Oraculum\Socket\Exceptions;

use Exception;
use Socket;

final class InvalidSocketException extends Exception
{
    /**
     * Creates a new instance of the class.
     * 
     * @param string               $message The Exception message to throw.
     * @param int                  $code    The Exception code.
     * @param resource|Socket|null $socket  A valid socket resource.
     * 
     * @return void
     */
    public function __construct($message = "", $code = 0, $socket = null)
    {
        $previous = null;

        if (!is_null($socket)) {
            // Retrieves the previous socket error message and code.
            $previousMessage = socket_strerror($previousCode = socket_last_error($socket));
    
            // Creates the previous exception with the socket error.
            $previous = new parent($previousMessage, $previousCode);
        }

        parent::__construct($message, $code, $previous);
    }
}