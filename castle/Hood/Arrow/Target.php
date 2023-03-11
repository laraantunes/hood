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
	 * All the controllers registered
	 * @var array
	 */
	protected static $controllers;

	/**
	 * Return the routes array
	 * @return array The current app routes
	 */
	public function getRoutes ()
	{
		return static::$routes;
	}
	/**
	 * Register a route and execute the callback function for the path
	 * @param  String   $method   The http method (get/post/etc...) Use 'any' for all
	 * @param  String   $path     The path for this method
	 * @param  mixed    $callback The callback function that'll be executed. Can be a function or a string with the
	 * name of a function
	 * @param  array    $options  Custom options for the callback function
	 * @example
	 * Hood\Arrow\Target::register('post', 'foo/bar', function($opt) { echo $opt['foo'];}, ['foo' => 'bar']);
	 */
	public static function register (String $method, String $path, $callback, array $options = null)
	{
		if (substr($path, -1, 1) != '/') {
			$path .= "/";
		}

		$method = strtoupper($method);
		static::$routes[$method][$path] = [
			'callback' => $callback,
			'options' => $options
		];
	}

	/**
	 * Register all the methods of a controller
	 * @param  string $controller Classname of the controller
	 */
	public static function registerController ($controller)
	{
		static::$controllers[] = $controller;
	}
}
