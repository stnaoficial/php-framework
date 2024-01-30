<?php declare(strict_types=1);

if (!function_exists('dump')) {
    /**
     * Dumps the given values.
     * 
     * @param mixed ...$values The values to dump.
     * 
     * @return void
     */
    function dump(...$values) {
        echo "<pre>";
        var_dump(...$values);
        echo "</pre>";
    }
}

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
        $basePath = \Oraculum\Support\Path::basePath($path);

        return $basePath;
    }
}