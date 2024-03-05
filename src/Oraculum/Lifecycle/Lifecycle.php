<?php

namespace Oraculum\Lifecycle;

use Oraculum\Support\Primitives\PrimitiveObject;
use Oraculum\Support\Traits\GloballyAvailable;

final class Lifecycle extends PrimitiveObject
{
    use GloballyAvailable;

    /**
     * @var array<int, Closure|string> $stages The stages of the lifecycle.
     */
    private $stages = [];

    /**
     * Creates a new instance of the class.
     * 
     * @param array<int, Closure|string> $stages The stages of the lifecycle.
     * 
     * @return void
     */
    public function __construct($stages = [])
    {
        $this->stages = $stages;
    }

    /**
     * Defines a new stage in the lifecycle.
     * 
     * @param int            $priority The priority of the stage.
     * @param Closure|string $handler  The handler for the stage.
     * 
     * @return void
     */
    public function stage($priority, $handler)
    {
        if (!isset($stages[$priority])) {
            $this->stages[$priority] = [];
        }

        $this->stages[$priority][] = $handler;
    }

    /**
     * Which begins the lifecycle.
     * 
     * @return void
     */
    public function begins()
    {
        foreach ($this->stages as $handlers) {
            foreach ($handlers as $handler) {
                call_user_func($handler);
            }
        }
    }
}