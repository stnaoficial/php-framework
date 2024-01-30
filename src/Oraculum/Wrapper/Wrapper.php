<?php

namespace Oraculum\Wrapper;

use Oraculum\Support\Primitives\PrimitiveObject;

/**
 * @template T
 */
final class Wrapper extends PrimitiveObject
{
    /**
     * The value to wrap.
     * 
     * @var T|null
     */
    public $value;

    /**
     * Creates a new instance of the class.
     * 
     * @param T|null $value The value to wrap.
     * 
     * @return void
     */
    public function __construct($value = null)
    {
        $this->value = $value;
    }

    /**
     * Wraps the value.
     * 
     * @param T $value The value to wrap.
     * 
     * @return void
     */
    public function wrap($value)
    {
        $this->value = $value;
    }

    /**
     * Unwraps the value.
     * 
     * @return T|null
     */
    public function unwrap()
    {
        return $this->value;
    }
}