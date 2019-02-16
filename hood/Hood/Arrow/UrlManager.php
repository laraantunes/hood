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
 * Url Manager class
 */
class UrlManager
{
    /**
     * Stores the instance of UrlManager object
     * @var \Hood\Arrow\UrlManager
     */
    protected static $instance;

    /**
     * Stores the Current routes
     * @var array
     */
    public $routes;

    /**
     * Stores the current path
     * @var string
     */
    public $path;

    /**
     * Stores the method
     * @var string
     */
    public $method;

    /**
     * Returns the current instance of UrlManager
     * @return \Hood\Arrow\UrlManager The current instance object
     */
    public static function getInstance()
    {
        if (empty(static::$instance)){
            static::$instance = new self;
        }
        return static::$instance;
    }

    /**
     * Constructor of the class
     */
    public function __construct()
    {
        // Load the routes
        $this->routes = \Hood\Arrow\Target::getRoutes();

        // The current path
        if (empty($_GET['get'])) {
            $this->path = '';
        } else {
            $this->path = $_GET['get'];
        }
        if (substr($this->path, -1, 1) != '/') {
            $this->path .= "/";
        }

        // The request method
        $this->method = $_SERVER['REQUEST_METHOD'];
    }


    /**
     * Call the route
     */
    public function call()
    {
        // If the route is not 'simple'
        if(empty($this->routes[$this->path][$this->method])){

            // If the route is not generic
            if (empty($this->routes[$this->path]['ANY'])) {
                // Get the dynamic routes
                // $dynamic = array_filter($this->routes, function($key) {
                //     return (boolean)static::isRegex($key);
                // }, ARRAY_FILTER_USE_KEY);

                // Get the regex routes
                $regex = array_filter($this->routes, function($key) {
                    return static::isRegex($key);
                }, ARRAY_FILTER_USE_KEY);

                foreach ($regex as $reg => $the_route) {
                    if (preg_match_all($reg, $this->path, $match)) {
                        // Get the params
                        $params = array_splice($match, 1, 1);

                        if (!empty($the_route[$this->method])) {
                            $route = $the_route[$this->method];
                        } elseif (!empty($the_route['ANY'])) {
                            $route = $the_route['ANY'];
                        } else {
                            throw new \Hood\Exceptions\NoRouteException;
                        }
                        break;
                    }
                }

                if (empty($route)) {
                    throw new \Hood\Exceptions\NoRouteException;
                }

            } else {
                $route = $this->routes[$this->path]['ANY'];
            }
        } else {
            $route = $this->routes[$this->path][$this->method];
        }

        if (!empty($params)) {
             var_dump($params);
            return call_user_func_array($route['callback'], $params);
        } else {
            return call_user_func($route['callback']);
        }
    }

    protected static function isRegex($str0) {
        $regex = "/^\/[\s\S]+\/$/";
        return preg_match($regex, $str0);
    }
}
