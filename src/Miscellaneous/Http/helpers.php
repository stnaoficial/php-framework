<?php declare(strict_types=1);

if (!function_exists('response')) {
    /**
     * Creates an HTTP response.
     * 
     * @param \Oraculum\Http\Content|string|null $content The content of the response.
     * 
     * @return \Miscellaneous\Http\Response Returns the HTTP response.
     */
    function response($content = null)
    {
        return new \Miscellaneous\Http\Response($content);
    }
}