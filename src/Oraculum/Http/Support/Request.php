<?php

namespace Oraculum\Http\Support;

use Oraculum\Support\Traits\NonInstantiable;

final class Request
{
    use NonInstantiable;

    /**
     * Gets the current HTTP method.
     * 
     * @return string
     */
    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Gets the current URI.
     * 
     * @return string
     */
    public static function uri()
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    /**
     * Gets all data passed via GET method.
     * 
     * @return array All data passed via GET method.
     */
    public static function get()
    {
        return $_GET;
    }

    /**
     * Gets all data passed via POST method.
     * 
     * @return array All data passed via POST method.
     */
    public static function post()
    {
        return $_POST;
    }

    /**
     * Gets all cookie data.
     * 
     * @return array All cookie data.
     */
    public static function cookies()
    {
        return $_COOKIE;
    }

    /**
     * Gets all passed source data.
     * 
     * @return array All passed source data.
     */
    public static function input()
    {
        parse_str(file_get_contents('php://input'), $data);

        if (is_array($data)) {
            return $data;
        }

        return [];
    }

    /**
     * Gets all body contents.
     * 
     * @return string All body contents.
     */
    public static function body()
    {
        return (string) file_get_contents('php://input');
    }

    /**
     * Gets all passed data as na associative array.
     * 
     * @return array All passed data as na associative array.
     */
    public static function parameters()
    {
        return array_merge(self::get(), self::post(), self::input());
    }
}