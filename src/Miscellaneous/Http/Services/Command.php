<?php

namespace Miscellaneous\Http\Services;

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
        $dependencies = [
            __SOURCE_DIR__ . "/Miscellaneous/Http/resources/commands/serve.php",
            __SOURCE_DIR__ . "/Miscellaneous/Http/resources/commands/broadcast.php",
            __SOURCE_DIR__ . "/Miscellaneous/Http/resources/commands/route.php"
        ];
        
        Autoloader::getInstance()
                  ->loadAll($dependencies);
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