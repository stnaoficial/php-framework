<?php

namespace Miscellaneous;

use Miscellaneous\Abstracts\ServiceProvider;
use Oraculum\Cli\Console;
use Oraculum\Cli\Request;
use Oraculum\Container\Container;
use Oraculum\Support\Environment as EnvironmentSupport;

class ConsoleServiceProvider extends ServiceProvider
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
        $this->initialize();
        $this->registerCommands();
        $this->bindContainerDependencies();
    }

    /**
     * Initializes the service provider.
     * 
     * @return void
     */
    private function initialize()
    {
        $this->container = Container::getInstance();
        $this->console   = Console::getInstance();
    }

    /**
     * Registers the commands.
     * 
     * @return void
     */
    private function registerCommands()
    {
        $this->console->setCommand(new Cli\Commands\HelpCommand($this->console));
        $this->console->setCommand(new Cli\Commands\CreateClassCommand());
        $this->console->setCommand(new Cli\Commands\ServeCommand());
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

        $this->container->call($command->getHandler());
    }
}