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
 * Trait for Routing and mapping a Resource with a dynamic controller
 */
trait RoutingResource
{
	/**
	 * Register the methods for controller
	 * @param  array  $methods Array with the methods. If null, register all methods, if not, register only the
	 * specific methods. Possible values: index show create update save delete
	 * @return object          The current object
	 */
	public function register($methods = null)
	{
		return $this;
	}

	public function registerController($controller = null)
	{
		if (empty($controller)){
			$controller = __CLASS__ . 'Controller';
		}

		
	}
}
