<?php

namespace Oraculum\Contracts;

interface Emptyable
{
    /**
     * Creates an empty instance of the class.
     * 
     * @return self The empty instance.
     */
    public static function empty();
}