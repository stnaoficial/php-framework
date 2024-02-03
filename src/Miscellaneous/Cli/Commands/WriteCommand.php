<?php

namespace Miscellaneous\Cli\Commands;

use Oraculum\Cli\Abstracts\Command;
use Oraculum\Cli\Console;

final class WriteCommand extends Command
{
    /**
     * The signature of the command.
     * 
     * @var string
     */
    protected $signature = "write";

    /**
     * The description of the command.
     * 
     * @var string|null
     */
    protected $description = "Allow you to write PHP code in the CLI.";

    /**
     * Creates a new instance of the class.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->registerHandler();
    }

    /**
     * Registers the command handler.
     * 
     * @return void
     */
    private function registerHandler()
    {
        $this->setHandler(function(Console $console) {
            $count = 0;
            $code = $line = '';

            while ("!!" !== $line = $console->ask($count . ' ')) {
                $code .= $line . PHP_EOL;
                $count++;
            }

            $console->writeLine(eval($code));
        });
    }
}