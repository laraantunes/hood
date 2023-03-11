<?php
/**
 * 2019 Hood Framework
 */

namespace Hood\Config;

/**
 * Class Params
 * @package Hood\Config
 */
class Params
{
    /**
     * @var \Glavic\Params
     */
    protected $paramsInstance;

    /**
     * @var self
     */
    protected static $singleton;

    /**
     * Params constructor.
     */
    public function __construct()
    {
        $this->paramsInstance = \Glavic\Params::getInstance();
    }

    /**
     * Gets a \Hood\Config singleton
     * @return Params
     */
    public static function singleton()
    {
        if (empty(static::$singleton)){
            static::$singleton = new self;
        }
        return static::$singleton;
    }

    /**
     * Gets the params object
     * @return \Glavic\Params|mixed
     */
    public static function params()
    {
        return self::singleton()->paramsInstance;
    }

    /**
     * Gets the server data with a key given
     * @param string $key
     * @return mixed
     */
    public static function server(string $key)
    {
        return self::singleton()->paramsInstance->server[$key];

    }

    /**
     * Gets the get data with a key given
     * @param string $key
     * @return mixed
     */
    public static function get(string $key)
    {
        return self::singleton()->paramsInstance->get[$key];

    }

    /**
     * Gets the post data with a key given
     * @param string $key
     * @return mixed
     */
    public static function post(string $key)
    {
        return self::singleton()->paramsInstance->post[$key];

    }

    /**
     * Gets the cookie data with a key given
     * @param string $key
     * @return mixed
     */
    public static function cookie(string $key)
    {
        return self::singleton()->paramsInstance->cookie[$key];

    }

    /**
     * Gets the session data with a key given
     * @param string $key
     * @return mixed
     */
    public static function session(string $key)
    {
        return self::singleton()->paramsInstance->session[$key];

    }
}