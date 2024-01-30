<?php declare(strict_types=1);

if (!function_exists('container')) {
    /**
     * Get the container instance.
     * 
     * @return \Oraculum\Container\Container The container instance.
     */
    function container()
    {
        $container = \Oraculum\Container\Container::getInstance();

        return $container;
    }
}