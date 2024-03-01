<?php

namespace Oraculum\Json\Support;

use Oraculum\Support\Traits\NonInstantiable;

final class Json
{
    use NonInstantiable;

    /**
     * Validates the JSON.
     * 
     * @param string $json The JSON.
     * @param int    $depth The maximum depth.
     * 
     * @return bool Returns `true` if the JSON is valid and `false` otherwise.
     */
    public static function validate($json, $depth = 512)
    {
        return @json_validate($json, $depth, JSON_INVALID_UTF8_IGNORE);
    }
}