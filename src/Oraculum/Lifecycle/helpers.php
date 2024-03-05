<?php declare(strict_types=1);

if (!function_exists('lifecycle')) {
    /**
     * Gets the lifecycle global instance.
     * 
     * @return \Oraculum\Lifecycle\Lifecycle Returns the lifecycle global instance.
     */
    function lifecycle()
    {
        return \Oraculum\Lifecycle\Lifecycle::getInstance();
    }
}