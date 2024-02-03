<?php declare(strict_types=1);

if (!function_exists('wrap')) {
    /**
     * Wraps a value.
     * 
     * @template T
     * 
     * @param T $value The value to wrap.
     * 
     * @return \Oraculum\Wrapper\Wrapper<T> The wrapper.
     */
    function wrap($value)
    {
        return new \Oraculum\Wrapper\Wrapper($value);
    }
}