<?php

namespace Oraculum\Http;

use InvalidArgumentException;
use Oraculum\Http\Exceptions\InvalidUriException;
use Oraculum\Http\Support\RoutePattern as RoutePatternSupport;
use Oraculum\Support\Contracts\Stringable;
use Oraculum\Support\Primitives\PrimitiveObject;

final class Route extends PrimitiveObject implements Stringable
{
    /**
     * @var array<int, string> $methods The route methods.
     */
    private $methods;

    /**
     * @var string $pattern The route pattern.
     */
    private $pattern;

    /**
     * @var Closure|string $handler The route handler.
     */
    private $handler;

    /**
     * Creates a new instance of the class.
     * 
     * @param array<int, string> $methods The route methods.
     * @param string             $pattern The route pattern.
     * @param Closure|string     $handler The route handler.
     * 
     * @return void
     */
    public function __construct($methods, $pattern, $handler)
    {
        $this->methods = $methods;
        $this->pattern = $pattern;
        $this->handler = $handler;
    }

    /**
     * Gets the route methods.
     * 
     * @return array<int, string> The route methods.
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * Gets the route pattern.
     * 
     * @return string The route pattern.
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * Gets the route pattern segments.
     * 
     * @return array<int, string> The route pattern segments.
     */
    public function getPatternSegments()
    {
        return explode('/', trim($this->pattern, '/'));
    }

    /**
     * Gets the route handler.
     * 
     * @return Closure|string The route handler.
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * Matches the route to the given URI.
     * 
     * @param Uri|string $uri The URI to match.
     * 
     * @return bool Returns `true` if the route matches the URI, `false` otherwise.
     */
    public function match($uri)
    {
        // Convert the URI to its corresponding object.
        // It avoids unnecessary conversions if the URI is already an object. 
        $uri = $uri instanceof Uri? $uri : new Uri($uri);

        if ($this->getPattern() === $uri->toString()) {
            return true;
        }

        $patternSegments = $this->getPatternSegments();

        foreach ($uriSegments = $uri->getSegments() as $index => $segment) {
            // Return false if there is no pattern related with the current
            // URI segment.
            if (empty($patternSegments[$index])) {
                return false;
            }
            
            $patternSegment = $patternSegments[$index];
            
            // Always return true if the pattern is a wildcard.
            if ($patternSegment === '*') {
                return true;
            }
            
            // Continue if the pattern has a parameter.
            // That means the URI segment is valid for any case.
            if (RoutePatternSupport::match($patternSegment)) {
                continue;
            }

            // Return false if the URI segment does not match the pattern.
            // That means the route does not match the URI.
            if ($patternSegment !== $segment) {
                return false;
            }
        }

        if (count($patternSegments) !== count($uriSegments)) {
            return false;
        }

        return true;
    }

    /**
     * Matches the route to the given URI and returns the matched parameters.
     * 
     * @param Uri|string $uri The URI to match.
     * 
     * @throws InvalidUriException If there is some required parameter missing in the URI.
     * 
     * @return array<string, string> Return the matched parameters.
     */
    public function matches($uri)
    {
        // Convert the URI to its corresponding object.
        // It avoids unnecessary conversions if the URI is already an object. 
        $uri = $uri instanceof Uri? $uri : new Uri($uri);

        $segments = $uri->getSegments();

        $params = [];

        foreach ($this->getPatternSegments() as $index => $segment) {
            // Continue if there is no parameter to match.
            if (!$param = RoutePatternSupport::match($segment)) {
                continue;
            }

            $uriSegmentIsEmpty = empty($segments[$index]);

            // Throw an exception if the parameter is not optional and the URI
            // segment is empty.
            if (!$param['optional'] && $uriSegmentIsEmpty) {
                throw new InvalidUriException(sprintf(
                    "Invalid URI %s. Missing required parameter [%s]", $uri->toString(), $param['name']
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
     * Gets the route URI instance with the given parameters.
     * 
     * @param array<string, string> $params The parameters of the route.
     * 
     * @throws InvalidArgumentException If there is some required parameter missing.
     * 
     * @return Uri Returns the URI instance with the given parameters.
     */
    public function uri($params = [])
    {
        $segments = [];

        foreach ($this->getPatternSegments() as $segment) {
            if (!$param = RoutePatternSupport::match($segment)) {
                $segments[] = $segment;
                continue;
            }

            $name = $param['name'];

            if (!$param['optional'] && !isset($params[$name])) {
                throw new InvalidArgumentException(sprintf(
                    "Missing required parameter [%s]", $name
                ));
            }

            $segments[] = $params[$name];
        }

        return  new Uri(implode('/', $segments));
    }

    /**
	 * Gets a string representation of the object.
     * 
     * @return string Returns the `string` representation of the object.
	 */
	public function __toString(): string
    {
        return $this->pattern;
    }

    /**
     * Gets a string representation of the object.
     * 
     * @return string Returns the `string` representation of the object.
     */
    public function toString()
    {
        return $this->__toString();
    }
}