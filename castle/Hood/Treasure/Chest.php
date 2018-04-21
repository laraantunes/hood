<?php

namespace Hood\Treasure;

class Chest {
    protected $con;

    public function __construct() {
        $this->start();
    }

    public static function getInstance(){
        if(!self::$instance){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function resetInstance(){
        self::$instance = null;
    }

    public function start() {
        
    }
}
