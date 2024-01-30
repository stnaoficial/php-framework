<?php

namespace Oraculum\Support;

use Oraculum\Support\Traits\NonInstantiable;

final class Path
{
    use NonInstantiable;

    /**
     * Get the base path of the given path.
     * 
     * @param string|null $path The path to get the base path from.
     * 
     * @return string The base path.
     */
    public static function basePath($path = null)
    {
        if (is_null($path)) {
            return __WORKING_DIR__;
        }

        return self::join(__WORKING_DIR__, $path);
    }

    /**
     * Exclude the basepath from a path.
     * 
     * @param string $path The path to exclude.
     * 
     * @return string The path without the basepath.
     */
    public static function excludeBasePath($path)
    {
        $path = str_replace(__WORKING_DIR__, '', $path);

        return rtrim($path, DIRECTORY_SEPARATOR);
    }

    /**
     * Join paths together.
     * 
     * @param string $basePath The base path to join.
     * @param string $paths    The rest of the paths to join.
     * 
     * @return string The joined path.
     */
    public static function join($basePath, ...$paths)
    {
        $fullpath = rtrim($basePath, DIRECTORY_SEPARATOR);

        foreach ($paths as $path) {
            $fullpath .= DIRECTORY_SEPARATOR . trim($path, DIRECTORY_SEPARATOR);
        }

        return $fullpath;
    }
}