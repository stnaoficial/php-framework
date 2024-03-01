<?php

namespace Oraculum\Http;

use Oraculum\Http\Exceptions\InvalidRouteException;
use Oraculum\Support\Primitives\PrimitiveObject;
use Oraculum\Support\Traits\GloballyAvailable;

final class Router extends PrimitiveObject
{
    use GloballyAvailable;

    /**
     * @var Fallback|null $fallback The fallback route.
     */
    private $fallback = null;

    /**
     * @var array<string, Route> $routes The registered routes.
     */
    private $routes = [];

    /**
     * Check if the router has a fallback route.
     * 
     * @return bool Returns `true` if the router has a fallback route, `false` otherwise.
     */
    public function hasFallback()
    {
        return !is_null($this->fallback);
    }

    /**
     * Get the fallback route.
     * 
     * @return Fallback|null Return the fallback route if set, `null` otherwise.
     */
    public function getFallback()
    {
        return $this->fallback;
    }

    /**
     * Set the fallback route.
     * 
     * @param Fallback $route The fallback route.
     * 
     * @return void
     */
    public function setFallback($route)
    {
        $this->fallback = $route;
    }

    /**
     * Gets an route for the given URI.
     * 
     * @param Uri|string $uri The URI to get the route from.
     * 
     * @return Route|null Returns the Route if found, `null` otherwise.
     */
    public function getRouteByUri($uri)
    {
        foreach ($this->routes as $route) {
            if ($route->match($uri)) {
                return $route;
            }
        }

        return null;
    }

    /**
     * Gets an route by its pattern.
     * 
     * @param string $pattern The route pattern.
     * 
     * @return Route|null Returns the Route if found, `null` otherwise.
     */
    public function getRouteByPattern($pattern)
    {
        return $this->routes[$pattern] ?? null;
    }

    /**
     * Register an route in the router.
     * 
     * @param Route $route The route to register.
     * 
     * @return void
     */
    public function setRoute($route)
    {
        $this->routes[$route->getPattern()] = $route;
    }

    /**
     * Get all the registered routes.
     * 
     * @return array<string, Route> The registered routes.
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Handle the given request and returns the corresponding route if available.
     * 
     * @param Request $request The request to handle.
     * 
     * @throws InvalidRouteException If the request is invalid.
     * @throws InvalidUriException   If the URI is invalid.
     * 
     * @return RouteFallback Returns the corresponding route.
     */
    public function handleRequest($request)
    {
        $uri = $request->uri();

        if ($route = $this->getRouteByUri($uri)) {
            if (!$request->isMethod(...$route->getMethods())) {
                throw new InvalidRouteException(sprintf(
                    "Invalid route method %s", $request->getMethod()
                ));
            }

            // Puts the URI parameters associated with the route in the request.
            // This allows the developer to access the route parameters directly
            // from the request.
            $request->putParameters($route->matches($uri));

        } else if ($this->hasFallback()) {
            $route = $this->getFallback();

        } else {
            throw new InvalidRouteException(sprintf(
                "Trying to access URI %s with method %s", $uri, $request->getMethod()
            ));
        }

        return $route;
    }
}