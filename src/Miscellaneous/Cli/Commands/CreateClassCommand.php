<?php

namespace Miscellaneous\Cli\Commands;

use Oraculum\Cli\Abstracts\Command;
use Oraculum\Cli\Console;
use Oraculum\Cli\Request;
use Oraculum\FileSystem\File;
use Oraculum\FileSystem\LocalFile;
use Oraculum\Stub\Stub;
use Oraculum\Support\Path as PathSupport;

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
    protected $description = "Create a new class with the especified [--namespace], [--class=<name>,...>] and [--destination].";

    /**
     * Handle the command.
     * 
     * @return void
     */
    protected function handle()
    {
        $console = new Console;

        $file = new File(__SOURCE_DIR__ . "/Miscellaneous/resources/stubs/class.stub");
        $stub = Stub::fromFile($file);

        $request = Request::fromCapture();

        $namespace   = $request->hasOption("namespace")?   $request->getOption("namespace")   : $console->ask("What is the namespace of the class? ");
        $class       = $request->hasOption("class")?       $request->getOption("class")       : $console->ask("What is the name of the class? ");
        $destination = $request->hasOption("destination")? $request->getOption("destination") : $console->ask("Where do you want to save the class? ");

        $classes = [$class];

        if (str_contains($class, ",")) {
            $classes = explode(",", $class);
        }

        foreach ($classes as $class) {
            $filename = PathSupport::join($destination, $class . PHP_FILE_EXTENSION);
    
            $result = $stub->compute(compact("namespace", "class"));
    
            $file = new LocalFile($filename);
    
            if ($file->exists()) {
                $file->clear();
            }
    
            $file->write($result);
        }
    }
}