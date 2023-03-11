<?php
/**
 * 2019 Hood Framework
 */

namespace Hood\Target;

use Lead\Net\Http\Cgi\Request;
use Lead\Router\Router;

/**
 * Class Route
 * @package Hood\Target
 */
class Route
{
    /**
     * @var \Lead\Router\Router
     */
    protected static $router;

    /**
     * @var \Lead\Net\Http\Cgi\Request;
     */
    protected static $request;

    /**
     * HTTP Methods
     * @var array
     */
    protected static $methods = [
        'GET',
        'POST',
        'PUT',
        'DELETE',
        'PATCH',
    ];

    /**
     * Gets the router
     * @return Router
     */
    public static function router()
    {
        if (empty(static::$router)){
            static::$router = new Router(['strategies' => [
                'resource' => new ResourceStrategy(),
            ]]);
            static::$router->basePath(APP_URL_BASE_PATH);
        }
        return static::$router;
    }

    public static function request()
    {
        if (empty(static::$request)){
            static::$request = Request::ingoing();
            static::$request->basePath(APP_URL_BASE_PATH);
        }
        return static::$request;
    }

    /**
     * Matches a method with the following pattern and binds the action
     * @param string $method
     * @param string $pattern
     * @param callable $action
     * @return mixed
     */
    public static function match(string $method, string $pattern, callable $action)
    {
        if (!in_array(strtoupper($method), self::$methods)) {
            return false;
        }

        $method = strtolower($method);
        self::_handleBasePattern($pattern);
        self::router()->$method($pattern, $action);
    }

    /**
     * Binds a get route
     * @param string $pattern
     * @param callable $action
     */
    public static function get(string $pattern, callable $action)
    {
        self::_handleBasePattern($pattern);
        self::router()->get($pattern, $action);
    }

    /**
     * Binds a post route
     * @param string $pattern
     * @param callable $action
     */
    public static function post(string $pattern, callable $action)
    {
        self::_handleBasePattern($pattern);
        self::router()->post($pattern, $action);
    }

    /**
     * Binds a put route
     * @param string $pattern
     * @param callable $action
     */
    public static function put(string $pattern, callable $action)
    {
        self::_handleBasePattern($pattern);
        self::router()->put($pattern, $action);
    }

    /**
     * Binds a patch route
     * @param string $pattern
     * @param callable $action
     */
    public static function patch(string $pattern, callable $action)
    {
        self::_handleBasePattern($pattern);
        self::router()->patch($pattern, $action);
    }

    /**
     * Binds a delete route
     * @param string $pattern
     * @param callable $action
     */
    public static function delete(string $pattern, callable $action)
    {
        self::_handleBasePattern($pattern);
        self::router()->delete($pattern, $action);
    }

    /**
     * Binds for all routes
     * @param string $pattern
     * @param callable $action
     */
    public static function all(string $pattern, callable $action)
    {
        self::_handleBasePattern($pattern);
        self::router()->bind($pattern, $action);
    }

    /**
     * Binds a resource controller class
     * @param string $class
     */
    public static function resource(string $class)
    {
        self::router()->resource($class);
    }

    /**
     * Handle for "/" pattern
     * @param string $pattern
     * @return string
     */
    protected static function _handleBasePattern(string &$pattern): string
    {
        if ($pattern == '/') {
            $pattern = APP_URL_BASE_PATH . '/';
        }
        return $pattern;
    }
}