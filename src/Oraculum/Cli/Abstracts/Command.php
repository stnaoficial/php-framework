<?php

namespace Oraculum\Cli\Abstracts;

use Oraculum\Support\Primitives\PrimitiveObject;

abstract class Command extends PrimitiveObject
{
    /**
     * The signature of the command.
     * 
     * @var string
     */
    protected $signature = 'unknown';

    /**
     * The description of the command.
     * 
     * @var string|null
     */
    protected $description = null;

    /**
     * @var Closure|string The command handler.
     */
    private $handler;

    /**
     * Get the signature of the command.
     * 
     * @return string The signature of the command.
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Get the description of the command.
     * 
     * @return string|null The description of the command.
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the handler of the command.
     * 
     * @param Closure|string $handler The handler of the command.
     * 
     * @return void
     */
    public function setHandler($handler)
    {
        $this->handler = $handler;
    }

    /**
     * Get the handler of the command.
     * 
     * @return Closure|string The handler of the command.
     */
    public function getHandler()
    {
        return $this->handler;
    }
}