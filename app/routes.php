<?php

declare (strict_types=1);

use \Hood\Target\Route;
error_reporting(E_ALL);

Route::get('/', function(){
    echo "index";

    // $t = new \Hood\Treasure\Test;
    // foreach ($t->teste() as $numero) {
    //     echo $numero;
    // }
});

Route::match('get', 'teste/url', function(){
    echo "teste url";
});

Route::match('get', 'teste/bla', function(){
    echo "teste";
});

Route::match('get', 'config/teste', function(){
    s(1);
});

Route::all('/^master\/(.*)\/blaster\/(.*)', function($param1, $param2){
    echo "regex";
    var_dump($param1, $param2);
});

Route::all('/^user\/(.*)', function($params){
    echo "regex";
    var_dump($params);
});

Route::match('get', 'builder', function(){
    echo \Hood\Treasure\Rogue\QueryBuilder::factory()->table('tabela')->field('campo');
    echo "<br>";
    echo \Hood\Treasure\Rogue\InsertBuilder::factory()->
        table('tabela')->
        value('campo', 10)->
        value('campo2', null)->
        value('campo3', '')->
        value('campo4', 1, \Hood\Treasure\Rogue\InsertBuilder::$TYPE_NUMBER);
    echo "<br>";
    echo \Hood\Treasure\Rogue\UpdateBuilder::factory()->
        table('tabela')->
        value('campo', 10)->
        value('campo2', null)->
        value('campo3', '')->
        value('campo4', 1, \Hood\Treasure\Rogue\UpdateBuilder::$TYPE_NUMBER)->
        where('campo', '?', \Hood\Treasure\Rogue\UpdateBuilder::$TYPE_BIND);
    echo "<br>";
});

Route::resource(\Controllers\ResourceTestController::class);
