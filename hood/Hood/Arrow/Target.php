<?php
/**
 * 2018 Hood Framework
 */
declare (strict_types=1);
/**
 * Namespace for Routing
 */
namespace Hood\Arrow;
/**
 * Routing Target Object
 */
class Target
{
    /**
     * Array for store routes
     * @var array
     */
    protected static $routes;

    /**
     * Return the routes array
     * @return array The current app routes
     */
    public static function getRoutes ()
    {
        return static::$routes;
    }
    /**
     * Register a route and execute the callback function for the path
     * @param  String   $method   The http method (get/post/etc...) Use 'any' for all
     * @param  String   $path     The path for this method
     * @param  mixed    $callback The callback function that'll be executed. Can be a function or a string with the
     * name of a function
     * @example
     * Hood\Arrow\Target::register('post', 'foo/bar', function() { echo 'foo'; });
     */
    public static function register (String $method, String $path, $callback)
    {
        if (substr($path, -1, 1) != '/') {
            $path .= "/";
        }

        $method = strtoupper($method);
        static::$routes[$path][$method] = [
            'callback' => $callback
        ];
    }
}
