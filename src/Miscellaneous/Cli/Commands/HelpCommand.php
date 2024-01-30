<?php

namespace Miscellaneous\Cli\Commands;

use Oraculum\Cli\Abstracts\Command;
use Oraculum\Cli\Console;

final class HelpCommand extends Command
{
    /**
     * The console instance.
     * 
     * @var Console
     */
    private $console;

    /**
     * The signature of the command.
     * 
     * @var string
     */
    protected $signature = "help";

    /**
     * The description of the command.
     * 
     * @var string|null
     */
    protected $description = "Print help information.";

    /**
     * Creates a new instance of the class.
     * 
     * @param \Oraculum\Cli\Console $console The console instance.
     * 
     * @return void
     */
    public function __construct($console)
    {
        $this->console = $console;
    }

    /**
     * Handle the command.
     * 
     * @return void
     */
    protected function handle()
    {
        $this->console->writeLine('Commands available:',
            Console::TEXT_BLUE | Console::TEXT_DECORATION_ITALIC | Console::TEXT_DECORATION_BOLD
        );

        foreach ($this->console->getCommands() as $signature => $command) {
            $this->console->write(sprintf("%' 30s ", $signature), Console::TEXT_MAGENTA);
            $this->console->writeLine($command->getDescription());
        }

        $this->console->writeLine(sprintf("%' 30s ", '...'));
    }
}