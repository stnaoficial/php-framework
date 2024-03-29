<?php

namespace Miscellaneous\Kernel\Abstracts;

use Oraculum\Support\Primitives\PrimitiveObject;

abstract class ServiceProvider extends PrimitiveObject
{
    /**
     * Provides the service.
     * 
     * @return void
     */
    public abstract function provide();
}