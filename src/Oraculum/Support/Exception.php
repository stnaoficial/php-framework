<?php

namespace Oraculum\Support;

use ErrorException;
use Oraculum\Support\Traits\NonInstantiable;
use Throwable;

final class Exception
{
    use NonInstantiable;

    /**
     * Gets an error exception from the given arguments.
     * 
     * @param int         $code     The error code.
     * @param string      $message  The error message.
     * @param string|null $filename The filename.
     * @param int|null    $line     The line number.
     * 
     * @return ErrorException The error exception.
     */
    private static function getErrorExceptionFromArguments($code = 0, $message = "", $filename = null, $line = null)
    {
        return new ErrorException($message, $code, 1, $filename, $line);
    }

    /**
     * Gets an exception from the given arguments.
     * 
     * @param array $args The given arguments.
     * 
     * @return ErrorException|Throwable The exception.
     */
    public static function getExceptionFromArguments($args)
    {
        $exception = reset($args);

        if ($exception instanceof Throwable) {
            return $exception;
        }

        $exception = self::getErrorExceptionFromArguments(...$args);

        return $exception;
    }
}