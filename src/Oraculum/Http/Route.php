<?php

namespace Oraculum\Http;

use Oraculum\Contracts\Stringable;
use Oraculum\Support\Primitives\PrimitiveObject;

final class Route extends PrimitiveObject implements Stringable
{
    /**
     * @var array<int, string> The route methods.
     */
    private $methods;

    /**
     * @var string The route pattern.
     */
    private $pattern;

    /**
     * @var Closure|string The route handler.
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
        $this->methods  = $methods;
        $this->pattern  = $pattern;
        $this->handler  = $handler;
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
}