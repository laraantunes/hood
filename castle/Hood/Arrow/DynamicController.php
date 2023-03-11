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
 * Dynamic Class Controller
 */
class DynamicController extends \Hood\Arrow\Controller
{
	/**
	 * Resource Classname
	 * @var string
	 */
	protected $resource;

	/**
	 * Constructor of \Hood\Arrow\DynamicController class
	 * @param mixed $resource Classname or Object for class
	 */
	public function __construct($resource)
	{
		if (!is_string($resource)){
			$this->resource = get_class($resource);
		} else {
			$this->resource = $resource;
		}
	}

	public function index()
	{
		echo 'index';
	}

	public function show()
	{
		echo 'show';
	}

	public function create()
	{
		echo 'create';
	}

	public function update()
	{
		echo 'update';
	}

	public function save()
	{
		echo 'save';
	}

	public function delete()
	{
		echo 'delete';
	}
}
