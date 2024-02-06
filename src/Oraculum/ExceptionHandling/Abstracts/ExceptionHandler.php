<?php

namespace Oraculum\ExceptionHandling\Abstracts;

use ErrorException;
use Oraculum\Support\Primitives\PrimitiveObject;
use Oraculum\Support\Traits\GloballyAvailable;
use Throwable;

abstract class ExceptionHandler extends PrimitiveObject
{
    use GloballyAvailable;

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
    private function getErrorExceptionFromArguments($code = 0, $message = "", $filename = null, $line = null)
    {
        return new ErrorException($message, $code, 1, $filename, $line);
    }

    /**
     * Gets an exception from the given arguments.
     * 
     * @param array $args The given arguments.
     * 
     * @return Throwable The exception.
     */
    private function getExceptionFromArguments($args)
    {
        $exception = reset($args);

        if ($exception instanceof Throwable) {
            return $exception;
        }

        $exception = self::getErrorExceptionFromArguments(...$args);

        return $exception;
    }

    /**
     * Gets the computed trace as string.
     * 
     * @param array $stack The exception stack trace.
     * 
     * @return string The computed trace as string.
     */
    protected function getComputedTraceAsString($stack)
    {
        $string = '';

        foreach ($stack as $trace) {
            if (!isset($trace['file'], $trace['line'])) {
                continue;
            }

            $string .= sprintf("%s:%s\n", $trace['file'], $trace['line']);
        }

        return $string;
    }

    /**
     * Restores the previous exception handler.
     * 
     * @return void
     */
    public function restore()
    {
        restore_exception_handler();
        restore_error_handler();
    }

    /**
     * Handles the given exception.
     * 
     * @param Throwable $exception The exception.
     * 
     * @return void
     */
    protected abstract function handle($exception);

    /**
     * Registers the exception handler.
     * 
     * @return void
     */
    public function register()
    {
        $handler = function(...$args) {
            $this->handle($this->getExceptionFromArguments($args));
        };

        set_exception_handler($handler);
        set_error_handler($handler);
    }
}