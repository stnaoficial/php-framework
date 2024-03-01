<?php

namespace Oraculum\Http\Support;

use Oraculum\Support\Traits\NonInstantiable;

final class RoutePattern
{
    use NonInstantiable;

    /**
     * Matches the given pattern searching for a parameter on it.
     * 
     * @param string $segment The pattern to match.
     * 
     * @return array{name: string, optional: bool}|false The pattern parameter or `false` if it doesn't match.
     */
    public static function match($segment)
    {
        preg_match('/\{([\w\:]+?)\??\}/', $segment, $matches);

        if (!isset($matches[0], $matches[1])) {
            return false;
        }

        // Determine if the match is optional by checking if it ends with a
        // question mark between the curly brackets.
        // e.g. {name?}
        $optional = str_ends_with($matches[0], '?}');
        
        // Get the name of the segment between the curly brackets.
        // e.g. {name}.
        $name = $matches[1];

        return compact('name', 'optional');
    }
}