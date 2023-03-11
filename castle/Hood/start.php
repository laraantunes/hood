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
define('HOOD_PATH', __DIR__ . DR . '..' . DR);

/**
 * Path for app files
 * @var string
 */
define('APP_PATH', __DIR__. DR . '..' . DR . '..' . DR . 'app' . DR);

/**
 * Path for config files
 * @var string
 */
define('CONFIG_PATH', __DIR__. DR . '..' . DR . '..' . DR . 'config' . DR);

$site_url_http = explode("/",$_SERVER['SERVER_PROTOCOL']);
$tmp_get_site_url = trim(str_replace($_GET['get'], "", $_SERVER["REQUEST_URI"]), "/");
$site_url = strtolower($site_url_http[0])."://".$_SERVER['SERVER_NAME'].(substr($_SERVER['SERVER_NAME'], strlen($_SERVER['SERVER_NAME']) - 1, 1) == "/" ? "" : "/").$tmp_get_site_url;
$site_url = trim($site_url, "/")."/";
/**
 * App's URL
 * @var string
 */
define('APP_URL', $site_url);

$currentUrl = 'http';
if(!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on"){
	$pageURL .= "s";
}
$currentUrl .= "://";
if($_SERVER["SERVER_PORT"] != "80"){
	$currentUrl .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
}else{
	$currentUrl .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
}
/**
 * Current URL
 * @var string
 */
define('CURRENT_URL', $currentUrl);

/**
 * Autoload Function
 * @param  string $class The Classname
 */
function __autoload($class)
{
	// Hood Classes
	if (file_exists(HOOD_PATH . str_replace('\\', '/', $class) . '.php')) {
		require_once (HOOD_PATH . str_replace('\\', '/', $class) . '.php');
	// App Classes
	} elseif (file_exists(APP_PATH . 'controllers' . str_replace('\\', '/', $class) . '.php')) {
		require_once file_exists(APP_PATH . 'controllers' . str_replace('\\', '/', $class) . '.php');
	} elseif (file_exists(APP_PATH . 'models' . str_replace('\\', '/', $class) . '.php')) {
		require_once file_exists(APP_PATH . 'models' . str_replace('\\', '/', $class) . '.php');
	}
}

/**
 * Include the routes
 */
include_once CONFIG_PATH . (defined('ENV') ? ENV . DR : '') . 'routes.php';

/**
 * Executes the action
 */
\Hood\Arrow\UrlManager::getInstance()->call();
