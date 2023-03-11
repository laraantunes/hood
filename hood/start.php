<?php
/**
 * Start file for Hood Framework
 * 2018 Hood Framework
 */

use \Hood\Config\Config as Config;

/**
 * Includes the boot configuration, autoload and constants
 */
include_once('boot.php');

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
include_once (HOOD_PATH . DR . 'boot' . DR . 'translation.php');

/**
 * Includes the routes
 */
include_once APP_PATH . 'routes.php';

/**
* Executes the action
*/
if (!phpunit_test()) {
    \Hood\Target\Arrow::aim();
}
