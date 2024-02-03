<?php

namespace Oraculum\Cli\Commands;

use Oraculum\Cli\Abstracts\Command;
use Oraculum\Cli\Console;
use Oraculum\Cli\Support\Ansi as AnsiSupport;

final class HelpCommand extends Command
{
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
     * @var array<array-key, Command> The list of commands.
     */
    private $commands;

    /**
     * Creates a new instance of the class.
     * 
     * @param array<array-key, Command> $commands The list of commands.
     * 
     * @return void
     */
    public function __construct($commands)
    {
        $this->commands = $commands;

        $this->registerHandler();
    }

    /**
     * Registers the command handler.
     * 
     * @return void
     */
    private function registerHandler()
    {
        $this->setHandler(function() {
            $console = Console::getInstance();

            $console->writeLine(AnsiSupport::format('Commands available:',
                AnsiSupport::DECORATION_ITALIC | AnsiSupport::DECORATION_BOLD
            ));

            // Puts the help command in the first position of the list of commands.
            // This helps to indentify it self as a command available.
            array_unshift($this->commands, $this);

            foreach ($this->commands as $command) {
                // Write the command signature in a bold text style.
                // This helps to identify the command signature in the CLI output.
                $console->write(AnsiSupport::format(sprintf("%' 30s ", $command->getSignature()),
                    AnsiSupport::DECORATION_BOLD
                ));

                $console->writeLine($command->getDescription());
            }

            $console->writeLine(sprintf("%' 30s ", '...'));
        });
    }
}