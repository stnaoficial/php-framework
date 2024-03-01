<?php

namespace Miscellaneous\Http\Commands;

use Oraculum\Cli\Console;
use Oraculum\Cli\Constracts\CommandHandler;
use Oraculum\Http\Router;
use Oraculum\Support\Primitives\PrimitiveObject;

final class RouteListCommand extends PrimitiveObject implements CommandHandler
{
    /**
     * @var Console $console The console instance.
     */
    private $console;

    /**
     * @var Router $router The router instance.
     */
    private $router;

    /**
     * Creates a new instance of the class.
     * 
     * @param Console $console The console instance.
     * @param Router  $router  The router instance.
     * 
     * @return void
     */
    public function __construct(Console $console, Router $router)
    {
        $this->console = $console;
        $this->router  = $router;
    }

    /**
     * Handles the command.
     * 
     * @return void
     */
    public function handle()
    {
        foreach ($this->router->getRoutes() as $route) {
            $this->console->writeLine($route->getPattern());
        }
    }
}