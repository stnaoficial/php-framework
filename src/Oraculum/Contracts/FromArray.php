<?php

namespace Oraculum\Contracts;

interface FromArray
{
    /**
     * Creates a new instance from an array.
     * 
     * @param array $array The array to create the instance.
     * 
     * @return self The new instance.
     */
    public static function fromArray($array);
}