<?php

namespace Miscellaneous\Cli\Commands;

use Oraculum\Cli\Abstracts\Command;
use Oraculum\Cli\Components\Table;
use Oraculum\Cli\Console;
use Oraculum\Cli\Request;
use Oraculum\Cli\Support\Ansi as AnsiSupport;
use Oraculum\FileSystem\File;
use Oraculum\Stub\Stub;
use Oraculum\Support\Path as PathSupport;
use Oraculum\Support\Performance;

final class CreateClassCommand extends Command
{
    /**
     * The signature of the command.
     * 
     * @var string
     */
    protected $signature = "create:class";

    /**
     * The description of the command.
     * 
     * @var string|null
     */
    protected $description = "Create a new class with the especified [--namespace], [--class=<name>,...>] and [--dirname].";

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

            $fields = [];

            $fields['namespace'] = $request->hasOption("namespace")? $request->getOption("namespace") : $console->ask("What is the namespace of the class? ");
            $class               = $request->hasOption("class")?     $request->getOption("class")     : $console->ask("What is the name of the class? ");
            $dirname             = $request->hasOption("dirname")?   $request->getOption("dirname")   : $console->ask("Where do you want to save the class? ");

            // Create an bidimensional array with the name of the directory,
            // the name of the file and the name of the class.
            // The remaining lines will be each of the files created.
            $data = [[
                AnsiSupport::format("Directory Name", AnsiSupport::DECORATION_BOLD),
                AnsiSupport::format("File Name", AnsiSupport::DECORATION_BOLD),
                AnsiSupport::format("Class Name", AnsiSupport::DECORATION_BOLD)
            ]];

            $stub = Stub::ofPhpClass();

            foreach (explode(',', $class) as $class) {
                $fields['class'] = $class;

                $filename = PathSupport::join($dirname, $class . PHP_FILE_EXTENSION);

                $file = new File($filename);

                $file->overwrite($stub->fill($fields));

                $data[] = [
                    DIRECTORY_SEPARATOR . $file->getDirectory(),
                    $file->getName() . '.' . $file->getExtension(),
                    $class
                ];
            }

            // Count decrements the first line of the table which is the header.
            // It is used to count the number of files created.
            $fileCount = count($data) - 1;

            $console->writeLine(sprintf(
                "%d files created in %.10f milliseconds.", $fileCount, Performance::end()
            ), 2);

            // Prints an table CLI component to make the output more readable.
            $console->writeLine(Table::new($data, 'single')->toString());
        });
    }
}