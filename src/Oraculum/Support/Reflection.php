<?php

namespace Oraculum\Support;

use Oraculum\Support\Traits\NonInstantiable;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

final class Reflection
{
    use NonInstantiable;

    /**
     * Checks if a given reflection has a specific attribute.
     *
     * @param ReflectionClass|ReflectionMethod|ReflectionProperty $reflection The reflection object to check.
     * @param string                                              $attribute  The class name of the attribute to check for.
     * 
     * @return bool Returns `true` if the reflection has the specified attribute, `false` otherwise.
     */
    public static function hasAttribute($reflection, $attribute)
    {
        $attributes = $reflection->getAttributes($attribute,
            ReflectionAttribute::IS_INSTANCEOF
        );

        return !empty($attributes);
    }

    /**
     * Retrieves the specified attribute from a Reflection object.
     * 
     * @template T of object
     * 
     * @param ReflectionClass|ReflectionMethod|ReflectionProperty $reflection The Reflection object to retrieve the attribute from.
     * @param class-string<T>                                     $attribute  The class name of the attribute to retrieve.
     * 
     * @return ReflectionAttribute<T>|null The retrieved attribute or null if not found.
     */
    public static function getAttribute($reflection, $attribute)
    {
        $attributes = $reflection->getAttributes($attribute, ReflectionAttribute::IS_INSTANCEOF);

        if (!empty($attributes)) {
            $attribute = reset($attributes);

            return $attribute;
        }

        return null;
    }
}