<?php

namespace Oraculum\Http;

use Oraculum\Support\Primitives\PrimitiveObject;

final class Fallback extends PrimitiveObject
{
    /**
     * @var Closure|string The route handler.
     */
    private $handler;

    /**
     * Creates a new instance of the class.
     * 
     * @param Closure|string $handler The fallback handler.
     * 
     * @return void
     */
    public function __construct($handler)
    {
        $this->handler = $handler;
    }

    /**
     * Gets the route handler.
     * 
     * @return Closure|string The route handler.
     */
    public function getHandler()
    {
        return $this->handler;
    }
}