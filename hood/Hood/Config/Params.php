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
     * @var self
     */
    protected static $singleton;

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
     * Gets the server data with a key given
     * @param string|null $key
     * @return mixed
     */
    public static function server(?string $key = null)
    {
        return $key ? $_SERVER[$key] ?? null : $_SERVER ?? [];

    }

    /**
     * Gets the get data with a key given
     * @param string|null $key
     * @return mixed
     */
    public static function get(?string $key = null)
    {
        return $key ? $_GET[$key] ?? null : $_GET ?? [];

    }

    /**
     * Gets the post data with a key given
     * @param string|null $key
     * @return mixed
     */
    public static function post(?string $key = null)
    {
        return $key ? $_POST[$key] ?? null : $_POST ?? [];

    }

    /**
     * Gets the cookie data with a key given
     * @param string $key
     * @return mixed
     */
    public static function cookie(?string $key = null)
    {
        return $key ? $_COOKIE[$key] ?? null : $_COOKIE ?? [];

    }

    /**
     * Gets the session data with a key given
     * @param string $key
     * @return mixed
     */
    public static function session(?string $key = null)
    {
        return $key ? $_SESSION[$key] ?? null : $_SESSION ?? [];

    }
}