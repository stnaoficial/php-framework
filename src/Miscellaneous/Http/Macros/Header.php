<?php

namespace Miscellaneous\Http\Macros;

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
     * Remove previously set headers.
     * 
     * @param string|null $name The name of the header to remove or `null` to remove all.
     * 
     * @return void
     */
    public static function undo($name = null)
    {
        BaseHeader::getInstance()->undo($name);
    }

    /**
     * Send all headers.
     * 
     * @param bool $replace If true the header will be replaced, otherwise it will be added.
     * @param int  $code    The HTTP status code.
     * 
     * @return void Returns `true` on success, `false` on failure.
     */
    public static function send($replace = true, $code = 0)
    {
        return BaseHeader::getInstance()->send($replace, $code);
    }

    /**
     * Check if headers have been sent.
     * 
     * @return array The headers sent.
     */
    public static function sent()
    {
        return HeaderSupport::sent();
    }
}