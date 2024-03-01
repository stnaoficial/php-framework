<?php

namespace Oraculum\Http\Macros;

use Oraculum\Http\Header as BaseHeader;
use Oraculum\Http\Support\Header as HeaderSupport;
use Oraculum\Support\Traits\Macroable;
use Oraculum\Support\Traits\NonInstantiable;

final class Header
{
    use NonInstantiable, Macroable;

    /**
     * Check if a header exists.
     * 
     * @param string $name The name of the header.
     * 
     * @return bool Returns `true` if the header is set, otherwise `false`.
     */
    public static function has($name)
    {
        return BaseHeader::getInstance()->has($name);
    }

    /**
     * Get a header value.
     * 
     * @param string $name The name of the header.
     * 
     * @return string|null Returns the header or `null` if the header is not set.
     */
    public static function get($name)
    {
        return BaseHeader::getInstance()->get($name);
    }

    /**
     * Set a header.
     * 
     * @param string $name  The name of the header.
     * @param string $value The value of the header.
     * 
     * @return void
     */
    public static function set($name, $value)
    {
        BaseHeader::getInstance()->set($name, $value);
    }

    /**
     * Send all headers.
     * 
     * @param bool $replace If true the header will be replaced, otherwise it will be added.
     * @param int  $code    The HTTP status code.
     * 
     * @return void
     */
    public static function send($replace = true, $code = 0)
    {
        BaseHeader::getInstance()->send($replace, $code);
    }

    /**
     * Gets a list of headers sent (or ready to send).
     * 
     * @return array The headers sent.
     */
    public static function sent()
    {
        return HeaderSupport::sent();
    }
}