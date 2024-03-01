<?php

namespace Oraculum\Http\Macros;

use Oraculum\Http\Enums\RequestMethod;
use Oraculum\Http\Fallback;
use Oraculum\Http\Route as BaseRoute;
use Oraculum\Http\Router;
use Oraculum\Support\Traits\Macroable;
use Oraculum\Support\Traits\NonInstantiable;

final class Route
{
    use NonInstantiable, Macroable;

    /**
     * @var string The group pattern.
     */
    private static $group = '';

    /**
     * Registers a group in the route macro.
     * 
     * @param string         $pattern The group pattern.
     * @param Closure|string $handler The group handler.
     * 
     * @return void
     */
    public static function group($pattern, $handler)
    {
        self::$group = $pattern;
        $handler();
        self::$group = '';
    }

    /**
     * Registers fallback in the router.
     * 
     * @param Closure|string $handler The fallback handler.
     * 
     * @return void
     */
    public static function fallback($handler)
    {
        Router::getInstance()->setFallback(new Fallback($handler));
    }

    /**
     * Registers a route in the router.
     * 
     * @param array<int, string> $methods The route methods.
     * @param string             $pattern The route pattern.
     * @param Closure|string     $handler The route handler.
     * 
     * @return \Oraculum\Http\Route Returns the registered route.
     */
    public static function match($methods, $pattern, $handler)
    {
        Router::getInstance()->setRoute($route = new BaseRoute($methods, self::$group . $pattern, $handler));

        return $route;
    }

    /**
     * Registers an `ANY` route in the router.
     * 
     * @param string         $pattern The route pattern.
     * @param Closure|string $handler The route handler.
     * 
     * @return \Oraculum\Http\Route Returns the registered route.
     */
    public static function any($pattern, $handler)
    {
        return self::match(RequestMethod::values(), $pattern, $handler);
    }

    /**
     * Registers a `GET` route in the router.
     * 
     * @param string         $pattern The route pattern.
     * @param Closure|string $handler The route handler.
     * 
     * @return \Oraculum\Http\Route Returns the registered route.
     */
    public static function get($pattern, $handler)
    {
        return self::match([RequestMethod::GET->value, RequestMethod::INFO->value], $pattern, $handler);
    }

    /**
     * Registers a `POST` route in the router.
     * 
     * @param string         $pattern The route pattern.
     * @param Closure|string $handler The route handler.
     * 
     * @return \Oraculum\Http\Route Returns the registered route.
     */
    public static function post($pattern, $handler)
    {
        return self::match([RequestMethod::POST->value], $pattern, $handler);
    }

    /**
     * Registers a `PUT` route in the router.
     * 
     * @param string         $pattern The route pattern.
     * @param Closure|string $handler The route handler.
     * 
     * @return \Oraculum\Http\Route Returns the registered route.
     */
    public static function put($pattern, $handler)
    {
        return self::match([RequestMethod::PUT->value], $pattern, $handler);
    }

    /**
     * Registers a `PATCH` route in the router.
     * 
     * @param string         $pattern The route pattern.
     * @param Closure|string $handler The route handler.
     * 
     * @return \Oraculum\Http\Route Returns the registered route.
     */
    public static function patch($pattern, $handler)
    {
        return self::match([RequestMethod::PATCH->value], $pattern, $handler);
    }

    /**
     * Registers a `DELETE` route in the router.
     * 
     * @param string         $pattern The route pattern.
     * @param Closure|string $handler The route handler.
     * 
     * @return \Oraculum\Http\Route Returns the registered route.
     */
    public static function delete($pattern, $handler)
    {
        return self::match([RequestMethod::DELETE->value], $pattern, $handler);
    }

    /**
     * Registers a `HEAD` route in the router.
     * 
     * @param string         $pattern The route pattern.
     * @param Closure|string $handler The route handler.
     * 
     * @return \Oraculum\Http\Route Returns the registered route.
     */
    public static function head($pattern, $handler)
    {
        return self::match([RequestMethod::HEAD->value], $pattern, $handler);
    }

    /**
     * Registers a `OPTIONS` route in the router.
     * 
     * @param string         $pattern The route pattern.
     * @param Closure|string $handler The route handler.
     * 
     * @return \Oraculum\Http\Route Returns the registered route.
     */
    public static function options($pattern, $handler)
    {
        return self::match([RequestMethod::OPTIONS->value], $pattern, $handler);
    }

    /**
     * Registers a `INFO` route in the router.
     * 
     * @param string         $pattern The route pattern.
     * @param Closure|string $handler The route handler.
     * 
     * @return \Oraculum\Http\Route Returns the registered route.
     */
    public static function info($pattern, $handler)
    {
        return self::match([RequestMethod::INFO->value], $pattern, $handler);
    }
}