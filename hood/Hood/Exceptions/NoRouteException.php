<?php

namespace Hood\Exceptions;

class NoRouteException extends \Exception
{
	/**
	 * Exception Message
	 * @see https://github.com/Philipp15b/php-i18n
	 * @var string
	 */
	protected $message = 'No Route for the specified path';
}
