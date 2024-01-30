<?php

namespace Oraculum\Http\Support;

use Oraculum\Support\Path as PathSupport;
use Oraculum\Support\Traits\NonInstantiable;

final class Server
{
    use NonInstantiable;

    public const HTTP_SERVER_PROTOCOL = 'HTTP/1.1';

    /**
     * Retrieves the current server protocol.
     * 
     * @return string The current server protocol.
     */
    public static function protocol()
    {
        return $_SERVER['SERVER_PROTOCOL'] ?? self::HTTP_SERVER_PROTOCOL;
    }

    /**
     * Returns the HTTP base url of the server.
     * 
     * @param string|null $uri The HTTP uri to append to the base url.
     * 
     * @return string The HTTP base url of the server.
     */
    public static function baseUrl($uri = null)
    {
        $protocol = 'http';

        if (isset($_SERVER["HTTPS"]) && strtoupper($_SERVER["HTTPS"]) == "ON") {
            $protocol = "https";
        }

        $name = $_SERVER["SERVER_NAME"] ?? 'localhost';

        $baseUrl = $protocol . '://' . $name;

        if (isset($_SERVER["SERVER_PORT"]) && $_SERVER["SERVER_PORT"] != "80") {
            $baseUrl =  $protocol . '://' . $name . ':' . $_SERVER["SERVER_PORT"];
        }

        if (!is_null($uri)) {
            return PathSupport::join($baseUrl, $uri);
        }

        return $baseUrl;
    }
}