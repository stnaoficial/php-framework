<?php

namespace Oraculum\Support;

use Oraculum\Support\Traits\NonInstantiable;

final class Value
{
    use NonInstantiable;

    /**
     * Checks if the value is printable.
     * 
     * @param mixed $value The value to check.
     * 
     * @return bool Returns true if the value is printable, false otherwise.
     */
    public static function isPrintable($value)
    {
        return is_string($value) || is_numeric($value) || is_bool($value);
    }
}