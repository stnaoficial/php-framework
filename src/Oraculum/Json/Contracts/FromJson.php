<?php

namespace Oraculum\Json\Contracts;

use Oraculum\Json\Json;

interface FromJson
{
    /**
     * Creates a new instance from an JSON.
     * 
     * @param Json|array|string $json The JSON to create the instance.
     * 
     * @return self The new instance.
     */
    public static function fromJson($json);
}