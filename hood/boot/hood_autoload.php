<?php
/**
 * 2025 Hood Framework
 */

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
    } elseif (file_exists(APP_PATH . str_replace('\\', '/', $class) . '.php')) {
        require_once (APP_PATH . str_replace('\\', '/', $class) . '.php');
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