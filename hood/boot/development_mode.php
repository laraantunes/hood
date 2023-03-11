<?php
/**
 * 2019 Hood Framework
 */

/**
 * Whoops for development environment
 */
if (dev() && !getenv('disable_whoops')) {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}