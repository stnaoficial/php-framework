<?php

namespace Miscellaneous\Cli\Services;

use Miscellaneous\Kernel\Abstracts\ServiceProvider;
use Oraculum\Cli\Console;
use Oraculum\Cli\Request;
use Oraculum\Container\Container;
use Oraculum\Support\Environment as EnvironmentSupport;

class Handling extends ServiceProvider
{
    /**
     * The container instance.
     * 
     * @var Container
     */
    private $container;

    /**
     * The console instance.
     * 
     * @var Console
     */
    private $console;

    /**
     * Creates a new instance of the class.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->initializeDependencies();
        $this->bindContainerDependencies();
    }

    /**
     * Initializes the service provider dependencies.
     * 
     * @return void
     */
    private function initializeDependencies()
    {
        $this->container = Container::getInstance();
        $this->console = Console::getInstance();
    }

    /**
     * Bind dependencies to the container.
     * 
     * @return void
     */
    private function bindContainerDependencies()
    {
        $this->container->bind(Console::class, function() {
            return $this->console;
        });

        $this->container->bind(Request::class, function() {
            return Request::fromCapture();
        });
    }

    /**
     * Provides the service.
     * 
     * @return void
     */
    public function provide()
    {
        if (!EnvironmentSupport::isCli()) {
            return;
        }

        /** @var Request */
        $request = $this->container->resolve(Request::class);

        $command = $this->console->handleRequest($request);

        if (!$command->hasHandler()) {
            return;
        }

        $this->container->call($command->getHandler());
    }
}