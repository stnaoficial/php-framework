<?php

namespace Miscellaneous\Alias\Macros;

use Miscellaneous\Alias\Network as AliasNetwork;
use Oraculum\Alias\Alias as BaseAlias;
use Oraculum\Support\Traits\Macroable;
use Oraculum\Support\Traits\NonInstantiable;

final class Alias
{
    use NonInstantiable, Macroable;

    /**
     * Determines if an alias exists.
     * 
     * @param string $name The alias name.
     * 
     * @return bool Returns `true` if the alias exists, `false` otherwise.
     */
    public static function has($name)
    {
        AliasNetwork::getInstance()->hasAlias($name);
    }

    /**
     * Gets an alias.
     * 
     * @template TValue
     * 
     * @param string $name The alias name.
     * 
     * @throws InvalidArgumentException If the alias does not exist.
     * 
     * @return TValue The alias value.
     */
    public static function get($name)
    {
        return AliasNetwork::getInstance()->getAlias($name);
    }

    /**
     * Sets an alias.
     * 
     * @template TValue
     * 
     * @param string $name      The alias name.
     * @param TValue $value     The alias value.
     * @param bool   $overwrite Determines whether the alias can be overwritten.
     * 
     * @throws BadMethodCallException If the alias cannot be overwritten.
     * 
     * @return void
     */
    public static function set($name, $value, $overwrite = true)
    {
        AliasNetwork::getInstance()->setAlias(new BaseAlias($name, $value, $overwrite));
    }
}