<?php declare(strict_types=1);

if (!function_exists('baseUrl')) {
    /**
     * Returns the HTTP base url of the server.
     * 
     * @param string|null $uri The HTTP uri to append to the base url.
     * 
     * @return string The HTTP base url of the server.
     */
    function baseUrl($uri = null)
    {
        $baseUrl = \Oraculum\Http\Support\Server::baseUrl($uri);

        return $baseUrl;
    }
}