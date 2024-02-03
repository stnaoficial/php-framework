<?php declare(strict_types=1);

if (!function_exists('basePath')) {
    /**
     * Get the base path of the given path.
     * 
     * @param string $path The path to get the base path from.
     * 
     * @return string The base path.
     */
    function basePath($path = null)
    {
        return \Oraculum\Support\Path::basePath($path);
    }
}