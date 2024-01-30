<?php

namespace Oraculum\Support;

final class NamespaceAlias
{
    /**
     * Encode a namespace into an alias.
     * 
     * @param string $namespace The namespace to encode.
     * 
     * @return string The encoded alias.
     */
    public static function encode($namespace)
    {
        $alias = str_replace(NAMESPACE_SEPARATOR, '.', $namespace);

        $alias = preg_replace('/\B([A-Z])/', '_$1', $alias);

        if (is_null($alias)) {
            return '';
        }

        return strtolower($alias);
    }

    /**
     * Decode an alias into a namespace.
     * 
     * @param string $alias The alias to decode.
     * 
     * @return string The decoded namespace.
     */
    public static function decode($alias)
    {
        $parts = explode('.', $alias);

        $alias = "";

        foreach ($parts as $part) {
            if (!$words = explode('_', $part)) {
                continue;
            }

            $part = implode('', array_map('ucfirst', $words));
            
            $alias .= NAMESPACE_SEPARATOR . $part;
        }

        return $alias;
    }
}