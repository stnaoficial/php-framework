<?php

namespace Oraculum\Support\Contracts;

interface Arrayable
{
    /**
     * Gets a array representation of the object.
     * 
     * @return array Returns the `array` representation of the object.
     */
    public function toArray();
}