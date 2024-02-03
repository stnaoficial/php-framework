<?php declare(strict_types=1);

if (!function_exists('dump')) {
    /**
     * Dump the given values.
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

if (!function_exists('kernel')) {
    /**
     * Get the kernel instance.
     * 
     * @return \Miscellaneous\Kernel The kernel instance.
     */
    function kernel()
    {
        return \Miscellaneous\Kernel::getInstance();
    }
}

if (!function_exists('alias')) {
    /**
     * Gets or sets an alias.
     * 
     * @template TValue
     * 
     * @param string      $name The alias name.
     * @param TValue|null $value The alias value.
     * @param bool        $overwrite Determines whether the alias can be overwritten.
     * 
     * @throws InvalidArgumentException If the alias does not exist.
     * @throws BadMethodCallException   If the alias cannot be overwritten.
     * 
     * @return TValue The alias value.
     */
    function alias($name, $value = null, $overwrite = true)
    {
        return \Miscellaneous\Kernel::getInstance()->alias($name, $value, $overwrite);
    }
}