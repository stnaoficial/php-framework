<?php
use Oraculum\Support\Traits\NonInstantiable;

final class Hash
{
    use NonInstantiable;

    /**
     * Encode a value using bcrypt algorithm.
     * 
     * @param string $value The value to encode.
     * 
     * @return string The encoded value.
     */
    public static function encode($value, $options = [])
    {
        return password_hash($value, PASSWORD_BCRYPT, $options);
    }

    /**
     * Check if a value matches a hash.
     * 
     * @param string $value The value to check.
     * @param string $hash  The hash to compare.
     * 
     * @return bool Returns `true` if the value matches the hash, `false` otherwise.
     */
    public static function check($value, $hash)
    {
        return password_verify($value, $hash);
    }
}