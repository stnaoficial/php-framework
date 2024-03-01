<?php

namespace Miscellaneous\Stub\Services;

use Miscellaneous\Kernel\Abstracts\ServiceProvider;
use Oraculum\FileSystem\File;

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
        File::new(__SOURCE_DIR__ . "/Miscellaneous/Stub/resources/commands.php")->require(false);
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