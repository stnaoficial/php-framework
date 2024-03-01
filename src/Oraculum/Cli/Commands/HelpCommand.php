<?php

namespace Oraculum\Cli\Commands;

use Oraculum\Cli\Console;
use Oraculum\Cli\Constracts\CommandHandler;
use Oraculum\Cli\Request;
use Oraculum\Cli\Support\Ansi as AnsiSupport;
use Oraculum\Support\Primitives\PrimitiveObject;

final class HelpCommand extends PrimitiveObject implements CommandHandler
{
    /**
     * @var Console $console The console instance.
     */
    private $console;

    /**
     * Creates a new instance of the class.
     * 
     * @param Console $console The console instance.
     * @param Request $request The CLI request instance.
     * 
     * @return void
     */
    public function __construct(Console $console)
    {
        $this->console = $console;
    }

    /**
     * Handles the command.
     * 
     * @return void
     */
    public function handle()
    {
        $this->console->writeLine(AnsiSupport::format('Commands available:',
            AnsiSupport::DECORATION_ITALIC | AnsiSupport::DECORATION_BOLD
        ));

        foreach ($this->console->getCommands() as $command) {
            // Write the command signature in a bold text style.
            // This helps to identify the command signature in the CLI output.
            $this->console->write(AnsiSupport::format(sprintf("%' 30s ", $command->getSignature()),
                AnsiSupport::DECORATION_BOLD
            ));

            // Write the description of the command if available.
            $this->console->writeLine($command->getDescription());
        }

        $this->console->writeLine(sprintf("%' 30s ", '...'));
    }
}