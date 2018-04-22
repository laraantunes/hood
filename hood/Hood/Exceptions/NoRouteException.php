<?php
/**
 * 2018 Hood Framework
 */
namespace Hood\Exceptions;
/**
 * Routing Exception
 * @package Hood\Exceptions
 * @author Maycow Alexandre Antunes <maycow@maycow.com.br>
 */
class NoRouteException extends \Exception
{
    /**
     * Exception Message
     * @see https://github.com/Philipp15b/php-i18n
     * @var string
     */
    protected $message = 'No Route for the specified path';
}
