<?php

namespace Oraculum\Cli\Macros;

use Oraculum\Cli\Command;
use Oraculum\Cli\Console as BaseConsole;
use Oraculum\Support\Traits\Macroable;
use Oraculum\Support\Traits\NonInstantiable;

final class Console
{
    use NonInstantiable, Macroable;

    /**
     * Registers a new command.
     * 
     * @param string              $signature   The signature of the command.
     * @param Closure|string|null $handler     The command handler.
     * @param string|null         $description The description of the command.
     * 
     * @return Command Returns the command instance.
     */
    public static function command($signature, $handler = null, $description = null)
    {
        BaseConsole::getInstance()->setCommand($command = new Command($signature, $handler, $description));

        return $command;
    }
}