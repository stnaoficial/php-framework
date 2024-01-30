<?php

namespace Oraculum\Support\Traits;

trait NonInstantiable
{
    /**
     * Creates a new instance of the class.
     * 
     * @return void
     */
    private function __construct()
    {
        //
    }
}