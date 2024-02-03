<?php

namespace Miscellaneous;

use Miscellaneous\Abstracts\ServiceProvider;
use Miscellaneous\ExceptionHandling\ExceptionHandler;

final class ExceptionHandlingServiceProvider extends ServiceProvider
{
    /**
     * @var ExceptionHandler The exception handler.
     */
    private $handler;

    /**
     * Creates a new instance of the class.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->initialize();
    }

    /**
     * Initializes the service provider.
     * 
     * @return void
     */
    public function initialize()
    {
        $this->handler = new ExceptionHandler;
    }

    /**
     * Provides the service.
     * 
     * @return void
     */
    public function provide()
    {
        $this->handler->restore();
        $this->handler->register();
    }
}
