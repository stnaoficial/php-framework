<?php

namespace Oraculum\Hydration;

use Oraculum\Support\Primitives\PrimitiveObject;
use ReflectionClass;

final class Hydrator extends PrimitiveObject
{
    /**
     * The reflection class.
     * 
     * @var ReflectionClass
     */
    private $reflection;

    /**
     * Creates a new instance of the class.
     * 
     * @param object $object The object to hydrate.
     * 
     * @return void
     */
    public function __construct($object)
    {
        $this->reflection = new ReflectionClass($object);
    }

    /**
     * Sets an instance variable.
     * 
     * @param object $instance The instance.
     * @param string $name     The name of the variable.
     * @param mixed  $value    The value of the variable.
     * 
     * @return void
     */
    private function setInstanceVariable($instance, $name, $value)
    {
        if (
            $this->reflection->hasProperty($name) &&
            !$this->reflection->getProperty($name)->isPrivate()
        ) {
            $this->reflection->getProperty($name)->setValue($instance, $value);
        }

        if (
            $this->reflection->hasMethod($name) &&
            !$this->reflection->getMethod($name)->isPrivate()
        ) {
            $this->reflection->getMethod($name)->invokeArgs($instance, $value);
        }
    }

    /**
     * Hydrates an object.
     * 
     * @param array $data The data to hydrate.
     * 
     * @return object The hydrated object.
     */
    public function hydrate($data)
    {
        $instance = $this->reflection->newInstanceWithoutConstructor();

        foreach ($data as $name => $value) {
            $this->setInstanceVariable($instance, $name, $value);
        }

        return $instance;
    }
}