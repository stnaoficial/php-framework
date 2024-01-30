<?php

namespace Oraculum\Support\Traits;

/**
 * @template T of static
 */
trait GloballyAvailable
{
    /**
     * The shared instance.
     * 
     * @var T
     */
    private static $instance;

    /**
     * Sets the shared instance.
     * 
     * @param T $newInstance The new instance to be set.
     * 
     * @return void
     */
    public static function setInstance($newInstance)
    {
        self::$instance = $newInstance;
    }

    /**
     * Gets the shared instance.
     * 
     * @return T The shared instance.
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::setInstance(new static);
        }

        return self::$instance;
    }
}