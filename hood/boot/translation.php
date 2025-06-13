<?php
/**
 * 2025 Hood Framework
 */

use \Hood\Config\Config as Config;
use \Hood\Config\I18n as I18n;

/**
 * Loads the translations
 */
if (!empty(Config::config()->app('i18n_class'))) {
    $i18n_class = Config::config()->app('i18n_class');
    $i18n = new $i18n_class();
} else {
    $i18n = new I18n();
}
$i18n->setCachePath(HOME_PATH . 'cache');
$i18n->setFilePath(HOME_PATH . 'language' . DR . '{LANGUAGE}.yml');
$i18n->setFallbackLang(
    !empty(Config::config()->app('language')) ? Config::config()->app('language') : 'pt-br'
);
$i18n->setMergeFallback(true);
$i18n->init();