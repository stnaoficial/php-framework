<?php declare(strict_types=1);

if (!function_exists('alias')) {
    /**
     * Gets or sets an alias.
     * 
     * @template TValue
     * 
     * @param string $name      The alias name.
     * @param TValue $value     The alias value.
     * @param bool   $overwrite Determines whether the alias can be overwritten.
     * 
     * @throws InvalidArgumentException If the alias does not exist.
     * @throws BadMethodCallException   If the alias cannot be overwritten.
     * 
     * @return TValue The alias value.
     */
    function alias($name, $value = null, $overwrite = true)
    {
        return \Oraculum\Alias\Network::getInstance()->alias($name, $value, $overwrite);
    }
}