<?php

namespace Miscellaneous\ExceptionHandling;

use Oraculum\Cli\Console;
use Oraculum\Cli\Support\Ansi as AnsiSupport;
use Oraculum\ExceptionHandling\Abstracts\ExceptionHandler as AbstractExceptionHandler;
use Oraculum\Support\Attributes\Override;
use Oraculum\Support\Environment;
use Throwable;

final class ExceptionHandler extends AbstractExceptionHandler
{
    /**
     * Handles the given exception.
     * 
     * @param Throwable $exception The exception.
     * 
     * @return void
     */
    #[Override]
    protected function handle($exception)
    {
        if (!Environment::isCli()) {
            throw $exception;
        }

        $console = Console::getInstance();

        $console->writeLine(AnsiSupport::format("Trace", AnsiSupport::DECORATION_BOLD));
        $console->writeLine($this->getComputedTraceAsString($exception->getTrace()));

        $console->writeLine(AnsiSupport::format("File", AnsiSupport::DECORATION_BOLD));
        $console->writeLine(sprintf("%s:%s", $exception->getFile(), $exception->getLine()), 2);

        $console->writeLine(AnsiSupport::format("Kind", AnsiSupport::DECORATION_BOLD));
        $console->writeLine(get_class($exception), 2);

        $console->writeLine(AnsiSupport::format("Message", AnsiSupport::DECORATION_BOLD));
        $console->writeLine($exception->getMessage());
    }
}