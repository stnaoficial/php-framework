<?php

namespace Miscellaneous\Kernel\Commands;

use Oraculum\Cli\Components\Panel;
use Oraculum\Cli\Console;
use Oraculum\Cli\Constracts\CommandHandler;
use Oraculum\FileSystem\File;
use Oraculum\Support\Primitives\PrimitiveObject;

final class LicenseCommand extends PrimitiveObject implements CommandHandler
{
    /**
     * @var Console $console The console instance.
     */
    private $console;

    /**
     * Creates a new instance of the class.
     * 
     * @param Console $console The console instance.
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
        $license = new File(__FRAMEWORK_DIR__ . "/LICENSE");

        if (!$license->exists()) {
            $this->console->writeLine("No license available.");
            return;
        }

        $panel = new Panel(theme: "single");

        $panel->load($license->read());

        $this->console->writeLine($panel);
    }
}