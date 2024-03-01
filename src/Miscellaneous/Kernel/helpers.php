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
     * @return \Miscellaneous\Kernel\Kernel The kernel instance.
     */
    function kernel()
    {
        return \Miscellaneous\Kernel\Kernel::getInstance();
    }
}