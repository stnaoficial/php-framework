<?php

namespace Oraculum\Support;

use Oraculum\Support\Traits\NonInstantiable;

final class Str
{
    use NonInstantiable;

    /**
     * Generates a random string.
     * 
     * @param int $length The length of the string.
     * 
     * @return string Returns the generated string.
     */
    public static function random(int $length = 16)
    {
        return substr(bin2hex(random_bytes($length)), 0, $length);
    }
}