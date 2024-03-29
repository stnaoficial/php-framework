<?php

namespace Oraculum\Support\Contracts;

interface FromCapture
{
    /**
     * Creates a new instance from an capture.
     * 
     * @return self The new instance.
     */
    public static function fromCapture();
}