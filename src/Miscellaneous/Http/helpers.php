<?php declare(strict_types=1);

if (!function_exists('response')) {
    /**
     * Creates an HTTP response from any data type.
     * 
     * @param mixed $data The data type to create the HTTP response.
     * 
     * @return \Miscellaneous\Http\Response Returns the HTTP response.
     */
    function response($data = null)
    {
        return \Miscellaneous\Http\Response::fromAny($data);
    }
}