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
	public function getInstance()
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
		$this->path = $_GET['get'];
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
		if(empty($this->routes[$this->method][$this->path])){

			// if()
			throw new \Hood\Exceptions\NoRouteException;
		}

		$route = $this->routes[$this->method][$this->path];

		if(!empty($route['options'])){
			return $route['callback']($route['options']);
		}else{
			return $route['callback']();
		}
	}
}
