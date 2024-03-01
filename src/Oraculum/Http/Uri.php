<?php

namespace Oraculum\Http;

use Oraculum\Support\Contracts\Stringable;
use Oraculum\Support\Primitives\PrimitiveObject;

class Uri extends PrimitiveObject implements Stringable
{
    /**
     * @var string $uri The URI.
     */
    protected $uri;

    /**
     * Creates a new instance of the class.
     * 
     * @param string $uri The URI.
     * 
     * @return void
     */
    public function __construct($uri)
    {
        $this->uri = $uri;
    }

    /**
     * Gets the URI segments.
     * 
     * @return array<int, string> The URI segments.
     */
    public function getSegments()
    {
        return explode('/', trim($this->uri, '/'));
    }

    /**
	 * Gets a string representation of the object.
     * 
     * @return string Returns the `string` representation of the object.
	 */
	public function __toString(): string
    {
        return $this->uri;
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