<?php

namespace Miscellaneous;

use Miscellaneous\Abstracts\ServiceProvider;
use Oraculum\Support\Exception as ExceptionSupport;

final class ExceptionServiceProvider extends ServiceProvider
{
    /**
     * Creates a new instance of the class.
     * 
     * @return void
     */
    public function __construct()
    {
        restore_exception_handler();
        restore_error_handler();

        $handler = function(...$args) {
            $exception = ExceptionSupport::getExceptionFromArguments($args);

            throw $exception;
        };

        set_exception_handler($handler);
        set_error_handler($handler);
    }

    /**
     * Provides the service.
     * 
     * @return void
     */
    public function provide()
    {
        //
    }
}
