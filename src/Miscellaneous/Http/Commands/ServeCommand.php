<?php

namespace Miscellaneous\Http\Commands;

use Oraculum\Cli\Components\Panel;
use Oraculum\Cli\Console;
use Oraculum\Cli\Constracts\CommandHandler;
use Oraculum\Cli\Process;
use Oraculum\Cli\Request;
use Oraculum\Cli\Support\Ansi as AnsiSupport;
use Oraculum\Support\Performance;
use Oraculum\Support\Primitives\PrimitiveObject;

final class ServeCommand extends PrimitiveObject implements CommandHandler
{
    /**
     * @var Console $console The console instance.
     */
    private $console;

    /**
     * @var Request $request The CLI request instance.
     */
    private $request;

    /**
     * Creates a new instance of the class.
     * 
     * @param Console $console The console instance.
     * @param Request $request The CLI request instance.
     * 
     * @return void
     */
    public function __construct(Console $console, Request $request)
    {
        $this->console = $console;
        $this->request = $request;
    }

    /**
     * Gets the server address.
     * 
     * @return string
     */
    private function getAddress()
    {
        return sprintf("%s:%s", $this->request->untilOption("host", "0.0.0.0"), $this->request->untilOption("port", 80));
    }

    /**
     * Gets the CLI panel info component.
     * 
     * @return Panel Returns the CLI panel info component.
     */
    private function getPanelInfo()
    {
        $panel = new Panel(theme: "single");

        $panel->line(AnsiSupport::format("Notice", AnsiSupport::DECORATION_BOLD), 2);

        $panel->line("Remember to anable the [--output-logs] option if you want", 1);
        $panel->line("to see the PHP standard output.", 1);

        return $panel;
    }

    /**
     * Handles the command.
     * 
     * @return void
     */
    public function handle()
    {
        $address = $this->getAddress();

        Performance::start();

        $process = new Process([PHP_BINARY, "-S", $address, __SOURCE_DIR__ . "/Miscellaneous/Http/resources/server.php"]);

        $process->run(function ($pipe, $line) use ($address) {
            // It verifies if the current PHP output line contains the "Permission denied"
            // message and writes an error message.
            if (str_contains($line, "Permission denied")) {
                $this->console->writeLine(sprintf(
                    "Failed to listen on %s. Permission denied.", $address
                ));
            }

            // It verifies if the current PHP output line contains the "Development Server"
            // message and masks it to the command format.
            else if (str_contains($line, "Development Server")) {
                $this->console->writeLine(sprintf(
                    "Development server started at http://%s in %.10f milliseconds.", $address, Performance::end()
                ), 2);

                $this->console->writeLine($this->getPanelInfo(), 2);

                $this->console->writeLine("Press Ctrl+C to stop.", 2);

                // Show an message of waiting for requests if output-logs is set.
                // Allow the user to know that the output is anabled.
                if ($this->request->hasOption("output-logs")) {
                    $this->console->writeLine("Waiting for requests...", 2);
                }
            }

            // Outputs the standard PHP output if the output-logs is set.
            else if ($this->request->hasOption("output-logs")) {
                $this->console->write($line);
            }

        }, Process::SKIP_OUTPUT_PIPE | Process::UNBLOCK_OUTPUT_STREAM);
    }
}