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
    protected $description = "Starts an development server specified by [--host=0.0.0.0] and [--port=80].";

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

        $process->setHandler(function ($pipe, $line) use ($console, $hostAndPort) {
            if (str_contains($line, "Permission denied")) {
                $console->writeLine(sprintf("Failed to listen on %s. Permission denied.", $hostAndPort), Console::TEXT_RED);
            }

            if (str_contains($line, "Development Server")) {
                $console->writeLine(sprintf("Development server started at http://%s.", $hostAndPort), Console::TEXT_BLUE);
            }
        });

        $process->run(
            Process::SKIP_OUTPUT_PIPE | Process::UNBLOCK_OUTPUT_STREAM
        );
    }
}