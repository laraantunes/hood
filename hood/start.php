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
 * Home Project Path
 * @var string
 */
define('HOME_PATH', __DIR__ . DR . '..' . DR);

/**
 * Path for Hood Framework files
 * @var string
 */
define('HOOD_PATH', __DIR__ . DR);

/**
 * Path for app files
 * @var string
 */
define('APP_PATH', __DIR__. DR . '..' . DR . 'app' . DR);

/**
 * Path for config files
 * @var string
 */
define('CONFIG_PATH', __DIR__. DR . '..' . DR . 'config' . DR);

if (!getenv('TESTING')) {
    $site_url_http = explode("/",$_SERVER['SERVER_PROTOCOL']);

    if (empty($_GET['get'])) {
        $tmp_get_site_url = trim($_SERVER["REQUEST_URI"], "/");
    } else {
        $tmp_get_site_url = trim(str_replace($_GET['get'], "", $_SERVER["REQUEST_URI"]), "/");
    }

    $site_url = strtolower($site_url_http[0]) .
        "://" .
        $_SERVER['SERVER_NAME'] .
        (substr($_SERVER['SERVER_NAME'], strlen($_SERVER['SERVER_NAME']) - 1, 1) == "/" ? "" : "/") .
        $tmp_get_site_url;
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
}

/**
 * Autoload Function
 * @param  string $class The Classname
 */
function HoodAutoload($class)
{
    // Hood Classes
    if (file_exists(HOOD_PATH . str_replace('\\', '/', $class) . '.php')) {
        require_once (HOOD_PATH . str_replace('\\', '/', $class) . '.php');
    // App Classes
    } elseif (file_exists(APP_PATH . 'controllers' . DR . str_replace('\\', '/', $class) . '.php')) {
        require_once APP_PATH . 'controllers' . DR . str_replace('\\', '/', $class) . '.php';
    } elseif (file_exists(APP_PATH . 'models' . DR . str_replace('\\', '/', $class) . '.php')) {
        require_once APP_PATH . 'models' . DR . str_replace('\\', '/', $class) . '.php';
    } elseif (file_exists(APP_PATH . 'misc' . DR . str_replace('\\', '/', $class) . '.php')) {
        require_once APP_PATH . 'misc' . DR . str_replace('\\', '/', $class) . '.php';
    }
}

ini_set('unserialize_callback_func', 'spl_autoload_call');
spl_autoload_register('HoodAutoload');

/**
 * Include the composer autoload
 */
if (file_exists(HOME_PATH . 'vendor' . DR . 'autoload.php')) {
    include_once HOME_PATH . 'vendor' . DR . 'autoload.php';
}

/**
 * Loads all the configurations
 */
$_HOOD_CONFIG = new \Hood\Config\Config();

/**
 * Include the routes
 */
include_once APP_PATH . 'routes.php';

/**
 * Loads the translations
 */
if (!empty($_HOOD_CONFIG->app('i18n_class'))) {
    $i18n_class = $_HOOD_CONFIG->app('i18n_class');
    $i18n = new $i18n_class();
} else {
    $i18n = new Hood\Config\I18n();
}
$i18n->setCachePath(HOME_PATH . 'cache');
$i18n->setFilePath(HOME_PATH . 'language' . DR . '{LANGUAGE}.yml');
$i18n->setFallbackLang(!empty($_HOOD_CONFIG->app('language')) ? $_HOOD_CONFIG->app('language') : 'pt-br');
$i18n->setMergeFallback(true);
$i18n->init();

/**
* Executes the action
*/
if (!getenv('TESTING')) {
    \Hood\Arrow\UrlManager::getInstance()->call();
}
