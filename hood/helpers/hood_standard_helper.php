<?php
/**
 * 2025 Hood Framework
 */

const DEVELOPMENT = 'development';
const TESTING = 'testing';
const PRODUCTION = 'production';
const ENVIRONMENT = 'environment';

/**
 * Throw a RuntimeException with the passed Exception
 * @param Throwable $t
 */
function ops(Throwable $t)
{
    throw new \RuntimeException($t->getMessage(), $t->getCode(), $t);
}

/**
 * Checks if the current environment is development
 * @return bool
 */
function dev(): bool
{
    return getenv(ENVIRONMENT) == DEVELOPMENT;
}

/**
 * Checks if the current environment is testing
 * @return bool
 */
function test(): bool
{
    return getenv(ENVIRONMENT) == TESTING;
}

/**
 * Checks if the current environment is production
 * @return bool
 */
function prod(): bool
{
    return getenv(ENVIRONMENT) == PRODUCTION;
}

/**
 * Checks if the current environment is testing
 * @return bool
 */
function phpunit_test(): bool
{
    return getenv(strtoupper(TESTING));
}

/**
 * Gets the shortName of a class - The ClassName without namespace
 * @param string $class
 * @return string
 * @throws ReflectionException
 */
function shortClassName(string $class): string
{
    return (new \ReflectionClass($class))->getShortName();
}

/**
 * Dump and Die
 * @param mixed $args
 */
function dd($args)
{
    var_dump($args); die;
}