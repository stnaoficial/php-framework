<?php

namespace Miscellaneous\Support\Contracts;

interface FromAny
{
    /**
     * Creates a new instance from any data type.
     * 
     * @param mixed $data The data to create the instance.
     * 
     * @return self The new instance.
     */
    public static function fromAny($data);
}