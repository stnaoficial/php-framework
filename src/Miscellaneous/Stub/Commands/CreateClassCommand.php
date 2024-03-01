<?php

namespace Miscellaneous\Stub\Commands;

use Oraculum\Cli\Components\Table;
use Oraculum\Cli\Console;
use Oraculum\Cli\Constracts\CommandHandler;
use Oraculum\Cli\Request;
use Oraculum\Cli\Support\Ansi as AnsiSupport;
use Oraculum\FileSystem\File;
use Oraculum\Stub\Stub;
use Oraculum\Support\Path as PathSupport;
use Oraculum\Support\Performance;
use Oraculum\Support\Primitives\PrimitiveObject;

final class CreateClassCommand extends PrimitiveObject implements CommandHandler
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
     * Handles the command.
     * 
     * @return void
     */
    public function handle()
    {
        $fields = [];

        // Asks for the required fields and saves them in an array.
        $fields["namespace"] = $this->request->askOption("namespace", $this->console,
            "What is the namespace of the classes? "
        );

        $classes = $this->request->askOption("classes", $this->console,
            "What is the name of the classes you want to create? (separated by comma) "
        );

        $dirname = $this->request->askOption("dirname", $this->console,
            "Where do you want to save the class? "
        );

        Performance::start();

        // Create an bidimensional array with the name of the directory,
        // the name of the file and the name of the class.
        // The remaining lines will be each of the files created.
        $data = [[
            AnsiSupport::format("Directory Name", AnsiSupport::DECORATION_BOLD),
            AnsiSupport::format("File Name", AnsiSupport::DECORATION_BOLD),
            AnsiSupport::format("Class Name", AnsiSupport::DECORATION_BOLD)
        ]];

        $stub = Stub::ofPhpClass();

        foreach (explode(",", $classes) as $class) {
            $fields["class"] = $class;

            $filename = PathSupport::join($dirname, $class . PHP_FILE_EXTENSION);

            $file = new File($filename);

            $file->overwrite($stub->fill($fields));

            $data[] = [
                DIRECTORY_SEPARATOR . $file->getDirectory(),
                $file->getName() . "." . $file->getExtension(),
                $class
            ];
        }

        // Count decrements the first line of the table which is the header.
        // It is used to count the number of files created.
        $fileCount = count($data) - 1;

        $this->console->writeLine(sprintf(
            "%d files created in %.10f milliseconds.", $fileCount, Performance::end()
        ), 2);

        // Prints an table CLI component to make the output more readable.
        $this->console->writeLine(Table::new($data, "single")->toString());
    }
}