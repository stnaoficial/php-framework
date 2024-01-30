<?php

namespace Oraculum\Cli\Support;

use Oraculum\Support\Traits\NonInstantiable;

final class Console
{
    use NonInstantiable;

    /**
     * Strictly converts a given value to its appropriate datatype.
     *
     * @param mixed $value The value to be converted.
     * 
     * @return mixed The converted value.
     */
    public static function strict($value)
    {
        switch (trim($value)) {
            case in_array(strtoupper($value), ['Y', 'YES', 'TRUE'], true):
                return true;
            case in_array(strtoupper($value), ['N', 'NO', 'FALSE'], true):
                return false;
            case is_numeric($value):
                return is_float($value + 0)? floatval($value) : intval($value);
            case is_string($value):
                return $value;
        }
    }
}