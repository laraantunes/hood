<?php

namespace Hood\Config;

use \Dotenv\Dotenv;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Config
 * @package Hood\Config
 */
class Config
{
    /**
     * Type "app"
     */
    const APP = 'app';

    /**
     * Type "env"
     */
    const ENV = 'env';

    /**
     * Type "database"
     */
    const DATABASE = 'database';

    /**
     * Type "params"
     * @todo get the params on Config object
     */
    const PARAMS = 'params';

    /**
     * @var array
     */
    protected $appConfig;

    /**
     * @var array
     */
    protected $envConfig;

    /**
     * @var array
     */
    protected $databaseConfig;

    /**
     * @var self
     */
    protected static $singleton;

    /**
     * Config constructor.
     */
    public function __construct()
    {
        if (file_exists(CONFIG_PATH . 'app.yml')) {
            $this->appConfig = Yaml::parseFile(CONFIG_PATH . 'app.yml');
        }

        $file = null;
        if (file_exists(HOME_PATH . '.env.local')) {
            $file = '.env.local';
        }
        Dotenv::createMutable(HOME_PATH, $file)->safeLoad();
        $this->envConfig = $_ENV;

        if (file_exists(CONFIG_PATH . 'database.yml')) {
            $this->databaseConfig = Yaml::parseFile(CONFIG_PATH . 'database.yml');
        }
    }

    /**
     * Gets the \Hood\Config singleton
     * @return Config
     */
    public static function config()
    {
        if (empty(static::$singleton)){
            static::$singleton = new self;
        }
        return static::$singleton;
    }

    /**
     * Starts the Hood configuration
     * @return Config
     */
    public static function start()
    {
        return self::config();
    }

    /**
     * gets an app configuration
     * @param $key
     * @return mixed|null
     */
    public function app($key)
    {
        return (!empty($this->appConfig[$key])) ? $this->appConfig[$key] : null;
    }

    /**
     * Gets an env configuration
     * @param $key
     * @return mixed|null
     */
    public function env($key)
    {
        return (!empty($this->envConfig[$key])) ? $this->envConfig[$key] : null;
    }

    /**
     * Gets an database configuration
     * @param $key
     * @return mixed|null
     */
    public function database($key)
    {
        return (!empty($this->databaseConfig[$key])) ? $this->databaseConfig[$key] : null;
    }

    /**
     * Gets a configuration based on singleton
     * @param string $type "app", "env" or "database'
     * @param string $key
     * @return mixed
     */
    public static function get(string $type, string $key)
    {
        return self::config()->$type($key);
    }
}
