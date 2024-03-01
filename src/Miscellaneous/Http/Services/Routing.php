<?php

namespace Miscellaneous\Http\Services;

use Miscellaneous\Kernel\Abstracts\ServiceProvider;
use Oraculum\Container\Container;
use Oraculum\Http\Contracts\Communicable;
use Oraculum\Http\Request;
use Oraculum\Http\Router;
use Oraculum\Support\Environment as EnvironmentSupport;

final class Routing extends ServiceProvider
{
    /**
     * The container instance.
     *
     * @var Container
     */
    private $container;

    /**
     * The router instance.
     *
     * @var Router
     */
    private $router;

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
        $this->router = Router::getInstance();
    }

    /**
     * Bind dependencies to the container.
     *
     * @return void
     */
    private function bindContainerDependencies()
    {
        $this->container->bind(Router::class, function() {
            return $this->router;
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
        if (EnvironmentSupport::isCli()) {
            return;
        }

        /** @var Request */
        $request = $this->container->resolve(Request::class);

        $route = $this->router->handleRequest($request);

        $return = $this->container->call($route->getHandler(), $request->getParameters());

        if (!$return instanceof Communicable) {
            return;
        }

        $return->send();
    }
}