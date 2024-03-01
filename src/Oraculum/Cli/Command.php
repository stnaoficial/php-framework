<?php

namespace Oraculum\Cli;

use Oraculum\Support\Primitives\PrimitiveObject;

final class Command extends PrimitiveObject
{
    /**
     * @var string $signature The signature of the command.
     */
    private $signature;

    /**
     * @var Closure|string|null $handler The command handler.
     */
    private $handler;

    /**
     * @var string|null $description The description of the command.
     */
    private $description;

    /**
     * Creates a new instance of the class.
     * 
     * @param string              $signature   The signature of the command.
     * @param Closure|string|null $handler     The command handler.
     * @param string|null         $description The description of the command.
     * 
     * @return void
     */
    public function __construct($signature, $handler = null, $description = null)
    {
        $this->signature   = $signature;
        $this->handler     = $handler;
        $this->description = $description;
    }

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
     * Check if the command has a handler.
     * 
     * @return bool Returns `true` if the command has a handler, `false` otherwise.
     */
    public function hasHandler()
    {
        return !is_null($this->handler);
    }

    /**
     * Get the handler of the command.
     * 
     * @return Closure|string|null The handler of the command.
     */
    public function getHandler()
    {
        return $this->handler;
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
     * Handles the command.
     * 
     * @param Closure|string $handler The handler of the command.
     * 
     * @return self Returns the current instance.
     */
    public function handle($handler)
    {
        $this->setHandler($handler);

        return $this;
    }

    /**
     * Check if the command has a description.
     * 
     * @return bool Returns `true` if the command has a description, `false` otherwise.
     */
    public function hasDescription()
    {
        return !is_null($this->description);
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
     * Set the description of the command.
     * 
     * @param string|null $description The description of the command.
     * 
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Describes the command.
     * 
     * @param string $description The description of the command.
     * 
     * @return self Returns the current instance.
     */
    public function describe($description)
    {
        $this->setDescription($description);

        return $this;
    }
}