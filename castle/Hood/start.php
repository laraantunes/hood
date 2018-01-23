<?php
/**
 * Start file for Hood Framework
 * 2018 Hood Framework
 */

/**
 * Shortcut for DIRECTORY_SEPARATOR
 * @var string
 */
define('DR', DIRECTORY_SEPARATOR);

/**
 * Path for Hood Framework files
 * @var string
 */
define('HOOD_PATH', __DIR__.DR.'..');

/**
 * Path for app files
 * @var string
 */
define('APP_PATH', __DIR__.DR.'..'.DR.'..'.DR.'app');

/**
 * Autoload Function
 * @param  string $class The Classname
 */
function __autoload($class)
{
	if (file_exists(HOOD_PATH.DR.str_replace('\\', '/', $class).'.php')) {
		require_once (HOOD_PATH.DR.str_replace('\\', '/', $class).'.php');
	} elseif (file_exists(APP_PATH.DR.'controllers'.str_replace('\\', '/', $class).'.php')) {
		require_once file_exists(APP_PATH.DR.'controllers'.str_replace('\\', '/', $class).'.php');
	} elseif (file_exists(APP_PATH.DR.'modelrs'.str_replace('\\', '/', $class).'.php')) {
		require_once file_exists(APP_PATH.DR.'models'.str_replace('\\', '/', $class).'.php');
	}
}
