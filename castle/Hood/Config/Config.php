<?php

namespace Hood\Config;

use \Dotenv\Dotenv;
use Symfony\Component\Yaml\Yaml;

class Config
{
    protected $appConfig;
    protected $envConfig;
    protected $databaseConfig;

    public function __construct()
    {
        if (file_exists(CONFIG_PATH . 'app.yml')) {
            $this->appConfig = Yaml::parseFile(CONFIG_PATH . 'app.yml');
        }

        $dotenv = new Dotenv(HOME_PATH);
        $dotenv->load();
        $this->envConfig = $_ENV;

        if (file_exists(CONFIG_PATH . 'database.yml')) {
            $this->databaseConfig = Yaml::parseFile(CONFIG_PATH . 'database.yml');
        }
    }

    public function app($key)
    {
        return (!empty($this->appConfig[$key])) ? $this->appConfig[$key] : null;
    }

    public function env($key)
    {
        return (!empty($this->envConfig[$key])) ? $this->envConfig[$key] : null;
    }

    public function database($key)
    {
        return (!empty($this->databaseConfig[$key])) ? $this->databaseConfig[$key] : null;
    }
}
