<?php

namespace Oraculum\Http\Support;

use Oraculum\Support\Path as PathSupport;
use Oraculum\Support\Traits\NonInstantiable;

final class Server
{
    use NonInstantiable;

    private const PROTOCOL_REVISION = 'HTTP/1.1';

    /**
     * Retrieves the current HTTP server version.
     * 
     * @return string The current HTTP server version.
     */
    public static function version()
    {
        // Gets the HTTP version.
        // e.g. HTTP/1.1
        return $_SERVER['SERVER_PROTOCOL'] ?? self::PROTOCOL_REVISION;
    }

    /**
     * Retrieves the current server protocol.
     * 
     * @return string The current server protocol.
     */
    public static function protocol()
    {
        $protocol = 'http';

        if (isset($_SERVER["HTTPS"]) && strtoupper($_SERVER["HTTPS"]) == "ON") {
            $protocol = "https";
        }

        return $protocol;
    }

    /**
     * Retrieves the current HTTP server host.
     * 
     * @return string The current HTTP server host.
     */
    public static function host()
    {
        return $_SERVER["SERVER_NAME"] ?? 'localhost';
    }

    /**
     * Retrieves the current HTTP server port.
     * 
     * @return string The current HTTP server port.
     */
    public static function port()
    {
        return $_SERVER["SERVER_PORT"] ?? '80';
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
        $protocol = self::protocol();

        $host = self::host();

        $baseUrl = $protocol . '://' . $host;

        if ('80' !== $port = self::port()) {
            $baseUrl =  $protocol . '://' . $host . ':' . $port;
        }

        if (!is_null($uri)) {
            return PathSupport::join($baseUrl, $uri);
        }

        return $baseUrl;
    }
}