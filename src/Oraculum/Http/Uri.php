<?php

namespace Oraculum\Http;

use Oraculum\Contracts\Stringable;
use Oraculum\Support\Primitives\PrimitiveObject;

class Uri extends PrimitiveObject implements Stringable
{
    /**
     * @var string The URI.
     */
    protected $value;

    /**
     * Creates a new instance of the class.
     * 
     * @param string $value The URI value.
     * 
     * @return void
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Gets the URI segments.
     * 
     * @return array<int, string> The URI segments.
     */
    public function getSegments()
    {
        return explode('/', trim($this->value, '/'));
    }

    /**
	 * Gets a string representation of the object.
     * 
     * @return string Returns the `string` representation of the object.
	 */
	public function __toString(): string
    {
        return $this->value;
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