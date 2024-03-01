<?php

namespace Oraculum\Html;

use Oraculum\Support\Contracts\Stringable;
use Oraculum\Html\Contracts\Htmlable;
use Oraculum\Support\Arr as ArraySupport;
use Oraculum\Support\Primitives\PrimitiveObject;

class Element extends PrimitiveObject implements Htmlable, Stringable
{
    /**
     * @var string The name of the element.
     */
    private $name;

    /**
     * @var array The attributes of the element.
     */
    private $attributes;

    /**
     * @var array The children of the element.
     */
    private $children;

    /**
     * Creates a new instance of the class.
     * 
     * @param string $name       The name of the element.
     * @param array  $attributes The attributes of the element.
     * @param array  $children   The children of the element.
     * 
     * @return void
     */
    public function __construct($name = 'div', $attributes = [], $children = [])
    {
        $this->name       = $name;
        $this->attributes = $attributes;
        $this->children   = ArraySupport::flatten($children);
    }

    /**
     * Gets the name of the element.
     * 
     * @return string The name of the element.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the attribute of the element.
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
     * Gets the attribute of the element.
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
     * Gets the attributes of the element.
     * 
     * @return array The attributes of the element.
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Appends a child to the element.
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
     * Gets the children of the element.
     * 
     * @return array The children of the element.
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Gets the HTML representation of the object.
     * 
     * @return string The HTML representation of the object.
     */
    public function toHtml()
    {
        return Parser::new()->parseElement($this->name, $this->attributes, $this->children);
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