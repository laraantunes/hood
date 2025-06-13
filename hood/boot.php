<?php
/**
 * 2025 Hood Framework
 */
use \Hood\Config\Config as Config;

/**
 * Shortcut for DIRECTORY_SEPARATOR
 * @var string
 */
const  DR = DIRECTORY_SEPARATOR;

/**
 * Home Project Path
 * @var string
 */
const HOME_PATH = __DIR__ . DR . '..' . DR;

/**
 * Path for Hood Framework files
 * @var string
 */
const HOOD_PATH = __DIR__ . DR;

/**
 * Path for app files
 * @var string
 */
const APP_PATH = __DIR__. DR . '..' . DR . 'app' . DR;

/**
 * Path for config files
 * @var string
 */
const CONFIG_PATH = __DIR__. DR . '..' . DR . 'config' . DR;

$base_path = str_replace(
    'hood' . DR . '..'. DR,
    '',
    str_replace(str_replace("/", "\\", $_SERVER["DOCUMENT_ROOT"]), "", HOME_PATH)
);
$base_path = str_replace(DR, '/', substr($base_path, 0, strlen($base_path) -1));
$base_path = str_replace($_SERVER["DOCUMENT_ROOT"], '', $base_path);

/**
 * Application's base path
 * @var
 */
define('APP_URL_BASE_PATH', $base_path);

/**
 * Hood's classes autoload
 */
include_once(HOOD_PATH . DR . 'boot' . DR . 'hood_autoload.php');

/**
 * Standard Hood Helper
 */
include_once(HOOD_PATH . DR . 'helpers' . DR . 'hood_standard_helper.php');

if (!phpunit_test()) {
    $site_url_http = explode("/", $_SERVER['SERVER_PROTOCOL']);
    $site_url = strtolower($site_url_http[0]) . "://" .$_SERVER['SERVER_NAME'] .APP_URL_BASE_PATH;
    $site_url = trim($site_url, "/")."/";

    /**
     * App's URL
     * @var string
     */
    define('APP_URL', $site_url);

    $currentUrl = 'http';
    if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $currentUrl .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $currentUrl .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $currentUrl .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    /**
     * Current URL
     * @var string
     */
    define('CURRENT_URL', $currentUrl);
}

// Custom Application Constants
include_once(CONFIG_PATH . 'constants.php');

/**
 * Loads all the application configurations
 */
Config::start();

/**
 * Handles the development mode
 */
include_once(HOOD_PATH . DR . 'boot' . DR . 'development_mode.php');

/**
 * Handles the translation
 */
include_once(HOOD_PATH . DR . 'boot' . DR . 'translation.php');

/**
 * Includes the routes
 */
include_once APP_PATH . 'routes.php';
