<?php

namespace Oraculum\Support\Primitives;

/**
 * @template T of static
 */
class PrimitiveObject
{
    /**
     * Creates a new instance of the class.
     * 
     * @param mixed $args Arguments to pass to the constructor.
     * 
     * @return T
     */
    final public static function new(...$args)
    {
        return new (static::class)(...$args);
    }
}