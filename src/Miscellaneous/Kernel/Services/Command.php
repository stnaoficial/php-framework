<?php

namespace Miscellaneous\Kernel\Services;

use Miscellaneous\Autoloader\Autoloader;
use Miscellaneous\Kernel\Abstracts\ServiceProvider;

final class Command extends ServiceProvider
{
    /**
     * Creates a new instance of the class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->requireDependencies();
    }

    /**
     * Requires the service provider dependencies.
     *
     * @return void
     */
    private function requireDependencies()
    {
        Autoloader::getInstance()
                  ->load(__SOURCE_DIR__ . "/Miscellaneous/Kernel/resources/commands.php");
    }

    /**
     * Provides the service.
     *
     * @return void
     */
    public function provide()
    {
        //
    }
}