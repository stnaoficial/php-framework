<?php

namespace Oraculum\Http;

use Oraculum\Http\Exceptions\InvalidRouteException;
use Oraculum\Http\Support\Route as RouteSupport;
use Oraculum\Support\Primitives\PrimitiveObject;
use Oraculum\Support\Traits\GloballyAvailable;

final class Router extends PrimitiveObject
{
    use GloballyAvailable;

    /**
     * @var \Oraculum\Http\Fallback|null The fallback route.
     */
    private $fallback = null;

    /**
     * @var array<string, \Oraculum\Http\Route> The registered routes.
     */
    private array $routes = [];

    /**
     * Checks if the given route matches the given URI.
     * 
     * @param Route $route The route to check.
     * @param Uri   $uri   The URI to check.
     * 
     * @return bool Returns `true` if the route matches the URI, `false` otherwise.
     */
    private function match($route, $uri)
    {
        if ($route->getPattern() === $uri->toString()) {
            return true;
        }

        $patternSegments = $route->getPatternSegments();

        foreach ($uriSegments = $uri->getSegments() as $index => $segment) {
            // Return false if there is no pattern related with the current
            // URI segment.
            if (empty($patternSegments[$index])) {
                return false;
            }
            
            $pattern = $patternSegments[$index];
            
            // Always return true if the pattern is a wildcard.
            if ($pattern === '*') {
                return true;
            }
            
            // Continue if the pattern has a parameter.
            // That means the URI segment is valid for any case.
            if (RouteSupport::matchPatternParameter($pattern)) {
                continue;
            }
            
            // Return false if the URI segment does not match the pattern.
            // That means the route does not match the URI.
            if ($pattern !== $segment) {
                return false;
            }
        }

        if (count($patternSegments) !== count($uriSegments)) {
            return false;
        }

        return true;
    }

    /**
     * Resolves the route parameters from the given URI.
     * 
     * @param Route $route The route to fill.
     * @param Uri   $uri   The URI to get the fields from.
     * 
     * @return array<int, string> The resolved route parameters.
     */
    private function resolve($route, $uri)
    {
        $segments = $uri->getSegments();

        $params = [];

        foreach ($route->getPatternSegments() as $index => $pattern) {
            // Continue if there is no parameter to match.
            if (false === $param = RouteSupport::matchPatternParameter($pattern)) {
                continue;
            }

            $uriSegmentIsEmpty = empty($segments[$index]);
            
            // Throw an exception if the parameter is not optional and the URI
            // segment is empty.
            if (!$param['optional'] && $uriSegmentIsEmpty) {
                throw new InvalidRouteException(sprintf(
                    "Invalid URI %s. Missing route parameter [%s]", $uri->toString(), $param['name']
                ));
            }
            
            // Otherwise, continue if the URI segment is empty.
            else if ($uriSegmentIsEmpty) {
                continue;
            }
            
            $params[$param['name']] = $segments[$index];
        }

        return $params;
    }

    /**
     * Check if the Router has a fallback route.
     * 
     * @return bool Returns `true` if the Router has a fallback route, `false` otherwise.
     */
    public function hasFallback()
    {
        return !is_null($this->fallback);
    }

    /**
     * Get the fallback route.
     * 
     * @return \Oraculum\Http\Fallback|null Return the fallback route if set, `null` otherwise.
     */
    public function getFallback()
    {
        return $this->fallback;
    }

    /**
     * Set the fallback route.
     * 
     * @param \Oraculum\Http\Fallback $route The fallback route.
     * 
     * @return void
     */
    public function setFallback($route)
    {
        $this->fallback = $route;
    }

    /**
     * Gets an Route from the Router.
     * 
     * @param Uri|string $uri The Route URI to get.
     * 
     * @return \Oraculum\Http\Route|null Returns the Route if found, `null` otherwise.
     */
    public function getRoute($uri)
    {
        foreach ($this->routes as $route) {
            if ($this->match($route, $uri)) {
                return $route;
            }
        }

        return null;
    }

    /**
     * Register an Route in the Router.
     * 
     * @param \Oraculum\Http\Route $route The Route to register.
     * 
     * @return void
     */
    public function setRoute($route)
    {
        $this->routes[$route->getPattern()] = $route;
    }

    /**
     * Handle the given request and returns the corresponding Route if available.
     * 
     * @param Request $request The request to handle.
     * 
     * @throws InvalidRouteException If the request is invalid.
     * 
     * @return \Oraculum\Http\Route|\Oraculum\Http\Fallback Returns the corresponding route.
     */
    public function handleRequest($request)
    {
        $uri = $request->uri();

        if ($route = $this->getRoute($uri)) {
            if (!$request->isMethod(...$route->getMethods())) {
                throw new InvalidRouteException(sprintf(
                    "Invalid route method %s", $request->getMethod()
                ));
            }
    
            $params = $this->resolve($route, $uri);
    
            $request->putParameters($params);

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