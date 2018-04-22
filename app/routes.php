<?php

declare (strict_types=1);

use \Hood\Arrow\Target;

Target::register('get', 'teste/url', function(){
    echo "teste";
});

Target::register('get', 'teste/bla', function(){
    echo "teste";
});

Target::register('get', 'config/teste', function(){
    new \Hood\Config\Config();
});

Target::register('all', '/', function(){
    echo "index";

    // $t = new \Hood\Treasure\Test;
    // foreach ($t->teste() as $numero) {
    //     echo $numero;
    // }
});

Target::register('all', '/^master\/(.*)\/blaster\/(.*)', function($param1, $param2){
    echo "regex";
    var_dump($param1, $param2);
});

Target::register('all', '/^user\/(.*)', function($params){
    echo "regex";
    var_dump($params);
});

Target::register('get', 'builder', function(){
    echo \Hood\Treasure\Rogue\QueryBuilder::factory()->table('tabela')->field('campo');
    echo "<br>";
    echo \Hood\Treasure\Rogue\InsertBuilder::factory()->table('tabela')->value('campo', 10);
    echo "<br>";
    echo \Hood\Treasure\Rogue\UpdateBuilder::factory()->
        table('tabela')->
        value('campo', 10)->
        where('campo', '?', \Hood\Treasure\Rogue\UpdateBuilder::$TYPE_BIND);
    echo "<br>";
});
