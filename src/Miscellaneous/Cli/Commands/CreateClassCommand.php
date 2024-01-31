<?php

namespace Miscellaneous\Cli\Commands;

use Oraculum\Cli\Abstracts\Command;
use Oraculum\Cli\Components\Table;
use Oraculum\Cli\Console;
use Oraculum\Cli\Request;
use Oraculum\FileSystem\StubFile;
use Oraculum\Support\Path as PathSupport;
use Oraculum\Support\Performance;

final class CreateClassCommand extends Command
{
    /**
     * The signature of the command.
     * 
     * @var string
     */
    protected $signature = "create-class";

    /**
     * The description of the command.
     * 
     * @var string|null
     */
    protected $description = "Create a new class with the especified [--namespace], [--class=<name>,...>] and [--dirname].";

    /**
     * Handle the command.
     * 
     * @return void
     */
    protected function handle()
    {
        Performance::start();

        $stub = new StubFile(__SOURCE_DIR__ . "/Miscellaneous/resources/stubs/class.stub");

        $request = Request::fromCapture();

        $console = new Console;

        $fields = [];

        $fields['namespace'] = $request->hasOption("namespace")? $request->getOption("namespace") : $console->ask("What is the namespace of the class? ");
        $class               = $request->hasOption("class")?     $request->getOption("class")     : $console->ask("What is the name of the class? ");
        $dirname             = $request->hasOption("dirname")?   $request->getOption("dirname")   : $console->ask("Where do you want to save the class? ");

        $classes = [$class];

        if (str_contains($class, ",")) {
            $classes = explode(",", $class);
        }

        // Create an bidimensional array with the name of the directory,
        // the name of the file and the name of the class.
        // The remaining lines will be each of the files created.
        $data = [[
            Console::format("Directory Name", Console::TEXT_DECORATION_BOLD),
            Console::format("File Name", Console::TEXT_DECORATION_BOLD),
            Console::format("Class Name", Console::TEXT_DECORATION_BOLD)
        ]];

        foreach ($classes as $class) {
            $fields['class'] = $class;

            $filename = PathSupport::join($dirname, $class . PHP_FILE_EXTENSION);

            $file = $stub->clone($filename, $fields);

            $data[] = [
                DIRECTORY_SEPARATOR . $file->getDirectory(),
                DIRECTORY_SEPARATOR . $file->getName() . '.' . $file->getExtension(),
                $class
            ];
        }

        // Count decrements the first line of the table which is the header.
        // It is used to count the number of files created.
        $fileCount = count($data) - 1;

        $console->writeLine(sprintf(
            "%d files created in %.10f milliseconds.", $fileCount, Performance::end())
        );

        // Prints an table CLI component to make the output more readable.
        $console->writeLine(Table::new($data)->toString());
    }
}