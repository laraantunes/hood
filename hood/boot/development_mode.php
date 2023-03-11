<?php
/**
 * 2019 Hood Framework
 */

/**
 * Whoops for development environment
 */
if (dev()) {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}