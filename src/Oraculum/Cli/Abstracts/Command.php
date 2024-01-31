<?php

namespace Oraculum\Cli\Abstracts;

use Closure;
use Oraculum\Cli\Request;
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
     * Handle the command.
     * 
     * @return void
     */
    protected abstract function handle();

    /**
     * Get the handler of the command.
     * 
     * @return Closure
     */
    public function getHandler()
    {
        return Closure::fromCallable([$this, 'handle']);
    }
}