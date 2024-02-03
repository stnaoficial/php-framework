<?php

namespace Oraculum\Support\Traits;

use BackedEnum;

/**
 * @template TValue
 * @template-implements BackedEnum<TValue, static>
 */
trait Enumerable
{
    /**
     * Checks if the given value is valid for the enum.
     * 
     * @param TValue $value The value to check.
     * 
     * @return bool Returns `true` if the value is valid, otherwise `false`.
     */
    public static function valid($value)
    {
        return self::tryFrom($value) !== null;
    }

    /**
     * Get an array of all names
     * 
     * @return array Returns all names
     */
    public static function names()
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * Get an array of all values
     * 
     * @return array Returns all values
     */
    public static function values()
    {
        return array_column(self::cases(), 'value');
    }
}