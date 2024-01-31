<?php

namespace Miscellaneous\Cli\Commands;

use Oraculum\Cli\Abstracts\Command;
use Oraculum\Cli\Console;
use Oraculum\Cli\Process;
use Oraculum\Cli\Request;
use Oraculum\FileSystem\File;
use UnexpectedValueException;

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
     * Handle the command.
     * 
     * @return void
     */
    protected function handle()
    {
        $console = new Console;

        $request = Request::fromCapture();

        $host = $request->hasOption("host")? $request->getOption("host") : "0.0.0.0";
        $port = $request->hasOption("port")? $request->getOption("port") : "80";

        $hostAndPort = $host . ':' . $port;

        $file = new File(__SOURCE_DIR__ . "/Oraculum/Http/server.php");

        if (!$file->exists()) {
            throw new UnexpectedValueException(sprintf(
                "File [%s] does not exist.", $file->getFilename()
            ));
        }

        $process = new Process([PHP_BINARY, "-S", $hostAndPort, $file->getFilename()]);

        $process->setHandler(function ($pipe, $line) use ($request, $console, $hostAndPort) {
            // It verifies if the current PHP output line contains the "Permission denied" message
            // and masks it to the command format.
            if (str_contains($line, "Permission denied")) {
                $console->writeLine(sprintf("Failed to listen on %s. Permission denied.", $hostAndPort));
                return;
            }

            // It verifies if the current PHP output line contains the "Development Server" message
            // and masks it to the command format.
            else if (str_contains($line, "Development Server")) {
                $console->writeLine(sprintf("Development server started at http://%s.", $hostAndPort));
                
                // Show an message of waiting for requests if output-logs is set.
                // Allow the user to know that the output is anabled.
                $request->hasOption('output-logs')
                ? $console->writeLine("Waiting for requests...")
                : null;
            }

            // Outputs the standard PHP output if the output-logs is set.
            else if ($request->hasOption('output-logs')) {
                $console->write($line);
            }
        });

        $process->run(
            Process::SKIP_OUTPUT_PIPE | Process::UNBLOCK_OUTPUT_STREAM
        );
    }
}