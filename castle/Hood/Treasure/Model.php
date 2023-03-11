<?php

namespace Hood\Treasure;

class Model {
    public static $table = "table";

    public static $fields = array("attribute" => "field");

    public static function getTable() {
        return static::$table;
    }

    public static function getFields() {
        return static::$fields;
    }

    
}
