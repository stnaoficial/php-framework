<?php

namespace Oraculum\Support\Traits;

use BadMethodCallException;
use Closure;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

trait Macroable
{
    /**
     * The registered string macros.
     *
     * @var array<string, Closure|string>
     */
    protected static array $macros = [];

    /**
     * Register a custom macro.
     *
     * @param string         $name  The name of the macro.
     * @param Closure|string $macro The macro implementation.
     * 
     * @return void
     */
    public static function macro($name, $macro)
    {
        static::$macros[$name] = $macro;
    }

    /**
     * Mix another object into the class.
     *
     * @param object $object  The object to be mixed into the class.
     * @param bool   $replace Whether to replace all existing macros.
     * 
     * @throws ReflectionException If the class does not exist.
     *
     * @return void
     */
    public static function mixin($object, $replace = true)
    {
        $methods = (new ReflectionClass($object))->getMethods(
            ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED
        );

        foreach ($methods as $method) {
            if ($replace || !static::hasMacro($method->name)) {
                static::macro($method->name, [$object, $method->name]);
            }
        }
    }

    /**
     * Checks if macro is registered.
     *
     * @param string $name The name of the macro.
     * 
     * @return bool Returns `true` if the macro is registered, `false` otherwise.
     */
    public static function hasMacro($name)
    {
        return isset(static::$macros[$name]);
    }

    /**
     * Flush the existing macros.
     *
     * @return void
     */
    public static function flushMacros()
    {
        static::$macros = [];
    }

    /**
     * Dynamically handle calls to the class.
     *
     * @param string $name The name of the method.
     * @param array  $args The arguments passed to the method.
     * 
     * @throws BadMethodCallException If the method does not exist.
     *
     * @return mixed The result of the method call.
     */
    private static function call($name, $args)
    {
        if (!static::hasMacro($name)) {
            throw new BadMethodCallException(sprintf(
                'Method [%s::%s] does not exist.', static::class, $name
            ));
        }

        $macro = static::$macros[$name];

        if ($macro instanceof Closure) {
            $macro = $macro->bindTo(null, static::class);
        }

        return $macro(...$args);
    }

    /**
     * Dynamically handle calls to the class.
     *
     * @param string $name The name of the method.
     * @param array  $args The arguments passed to the method.
     * 
     * @throws BadMethodCallException If the method does not exist.
     *
     * @return mixed The result of the method call.
     */
    public static function __callStatic($name, $args)
    {
        return self::call($name, $args);
    }

    /**
     * Dynamically handle calls to the class.
     *
     * @param string $name The name of the method.
     * @param array  $args The arguments passed to the method.
     * 
     * @throws BadMethodCallException If the method does not exist.
     *
     * @return mixed The result of the method call.
     */
    public function __call($name, $args)
    {
        return self::call($name, $args);
    }
}