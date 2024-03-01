<?php

namespace Oraculum\Container\Support;

use Closure;
use Oraculum\Container\Exceptions\BindingResolutionException;
use Oraculum\Support\Traits\NonInstantiable;
use UnexpectedValueException;

final class BoundMethod
{
    use NonInstantiable;

    /**
     * Returns the given Closure and inject its dependencies if necessary.
     * 
     * @param \Oraculum\Container\Container $container The container instance.
     * @param Closure|array|string          $callback  The callback to resolve.
     *
     * @throws UnexpectedValueException   If the callback is not valid.
     * @throws BindingResolutionException If the class or method can not be resolved.
     * 
     * @return Closure|string The resolved callback.
     */
    public static function closure($container, $callback)
    {
        if (is_string($callback) || $callback instanceof Closure) {
            return $callback;
        }

        if (!is_array($callback)) {
            throw new UnexpectedValueException(
                "Invalid closure type."
            );
        }

        list($name, $method) = $callback;

        if (!is_string($name)) {
            throw new UnexpectedValueException(
                "Invalid closure type."
            );
        }

        $instance = $container->resolve($name);

        return Closure::fromCallable([$instance, $method]);
    }
}