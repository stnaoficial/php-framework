<?php

namespace Miscellaneous\Cli\Commands;

use Oraculum\Cli\Abstracts\Command;
use Oraculum\Cli\Components\Panel;
use Oraculum\Cli\Console;
use Oraculum\Cli\Process;
use Oraculum\Cli\Request;
use Oraculum\Cli\Support\Ansi as AnsiSupport;
use Oraculum\Support\Performance;

final class ServeCommand extends Command
{
    /**
     * The signature of the command.
     * 
     * @var string
     */
    protected $signature = "serve";

    /**
     * The description of the command.
     * 
     * @var string|null
     */
    protected $description = "Starts an development server specified by [--host=0.0.0.0], [--port=80] and [--output-logs].";

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
        $this->setHandler(function(Console $console, Request $request) {
            Performance::start();

            $host = $request->hasOption("host")? $request->getOption("host") : "0.0.0.0";
            $port = $request->hasOption("port")? $request->getOption("port") : "80";

            $hostAndPort = $host . ':' . $port;

            $process = new Process([PHP_BINARY, "-S", $hostAndPort, __SOURCE_DIR__ . "/Miscellaneous/Http/resources/server.php"]);

            $process->setHandler(function ($pipe, $line) use ($request, $console, $hostAndPort) {
                // It verifies if the current PHP output line contains the "Permission denied"
                // message and writes an error message.
                if (str_contains($line, "Permission denied")) {
                    $console->writeLine(sprintf(
                        "Failed to listen on %s. Permission denied.", $hostAndPort
                    ));
                }

                // It verifies if the current PHP output line contains the "Development Server"
                // message and masks it to the command format.
                else if (str_contains($line, "Development Server")) {
                    $console->writeLine(sprintf(
                        "Development server started at http://%s in %.10f milliseconds.", $hostAndPort, Performance::end()
                    ), 2);

                    $panel = new Panel(theme: 'single');

                    $panel->line(AnsiSupport::format("Notice", AnsiSupport::DECORATION_BOLD), 2);

                    $panel->line("Remember to anable the [--output-logs] option if you want", 1);
                    $panel->line("to see the PHP standard output.", 1);

                    $console->writeLine($panel, 2);

                    $console->writeLine("Press Ctrl+C to stop.", 2);

                    // Show an message of waiting for requests if output-logs is set.
                    // Allow the user to know that the output is anabled.
                    if ($request->hasOption('output-logs')) {
                        $console->writeLine("Waiting for requests...", 2);
                    }
                }

                // Outputs the standard PHP output if the output-logs is set.
                else if ($request->hasOption('output-logs')) {
                    $console->write($line);
                }
            });

            $process->run(
                Process::SKIP_OUTPUT_PIPE | Process::UNBLOCK_OUTPUT_STREAM
            );
        });
    }
}