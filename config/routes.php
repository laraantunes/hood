<?php

declare (strict_types=1);

use \Hood\Arrow\Target as Target;

Target::register('get', 'teste/url', function(){
    echo "teste";
});

Target::register('get', 'teste/bla', function(){
    echo "teste";
});

Target::register('all', '/', function(){
    echo "index";

    $t = new \Hood\Treasure\Test;
    foreach ($t->teste() as $numero) {
        echo $numero;
    }
});

Target::register('all', '/^master\/(.*)\/blaster\/(.*)', function($param1, $param2){
    echo "regex";
    var_dump($param1, $param2);
});

Target::register('all', '/^user\/(.*)', function($params){
    echo "regex";
    var_dump($params);
});
