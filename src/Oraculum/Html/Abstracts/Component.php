<?php

namespace Oraculum\Html\Abstracts;

use Oraculum\Support\Contracts\Stringable;
use Oraculum\Html\Contracts\Htmlable;
use Oraculum\Support\Arr as ArraySupport;
use Oraculum\Support\Primitives\PrimitiveObject;

abstract class Component extends PrimitiveObject implements Htmlable, Stringable
{
    /**
     * @var array The attributes of the component.
     */
    protected $attributes;

    /**
     * @var array The children of the component.
     */
    protected $children;

    /**
     * Creates a new instance of the class.
     * 
     * @param array  $attributes The attributes of the component.
     * @param array  $children   The children of the component.
     * 
     * @return void
     */
    public function __construct($attributes = [], $children = [])
    {
        $this->attributes  = $attributes;
        $this->children    = ArraySupport::flatten($children);
    }

    /**
     * Sets the attribute of the component.
     * 
     * @param string $name  The name of the attribute.
     * @param mixed  $value The value of the attribute.
     * 
     * @return void
     */
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Gets the attribute of the component.
     * 
     * @param string $name The name of the attribute.
     * 
     * @return mixed The value of the attribute.
     */
    public function getAttribute($name)
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * Gets the attributes of the component.
     * 
     * @return array The attributes of the component.
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Appends a child to the component.
     * 
     * @param mixed $child The child to append.
     * 
     * @return void
     */
    public function appendChild($child)
    {
        $this->children[] = $child;
    }

    /**
     * Gets the children of the component.
     * 
     * @return array The children of the component.
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Defines the HTML component composition.
     * 
     * @return string Returns the HTML component composition.
     */
    protected abstract function compose();

    /**
     * Gets the HTML representation of the object.
     * 
     * @return string The HTML representation of the object.
     */
    public function toHtml()
    {
        return $this->compose();
    }

    /**
	 * Gets a string representation of the object.
     * 
     * @return string Returns the `string` representation of the object.
	 */
	public function __toString(): string
    {
        return $this->toHtml();
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