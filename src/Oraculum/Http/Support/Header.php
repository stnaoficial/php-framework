<?php

namespace Oraculum\Http\Support;

use Oraculum\Support\Traits\NonInstantiable;

final class Header
{
    use NonInstantiable;

    /**
     * Gets a list of headers sent (or ready to send).
     * 
     * @return array The headers sent.
     */
    public static function sent()
    {
        $headers = [];

        foreach (headers_list() as $header) {
            list($name, $value) = explode(': ', $header);
            $headers[trim($name)] = trim($value);
        }

        return $headers;
    }

    /**
     * Gets a list of current headers.
     * 
     * @return array The current headers.
     */
    public static function current()
    {
        if ($headers = getallheaders()) {
            return $headers;
        }

        return [];
    }
}