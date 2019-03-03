<?php

declare (strict_types=1);

use \cebe\markdown\MarkdownExtra;
use \Hood\Treasure\Chest;
use \Hood\Target\Route;
use \Hood\Toolbox\FileManager;
error_reporting(E_ALL);

Route::get('/', function(){
    echo "index";

    // $t = new \Hood\Treasure\Test;
    // foreach ($t->teste() as $numero) {
    //     echo $numero;
    // }
});

Route::get('doc', function() {
    $fm = new FileManager(HOME_PATH . 'docs');
    echo "<ul>";
    foreach ($fm->getFiles() as $file) {
        echo "<li>";
        echo "<a href='".APP_URL."doc/{$file}'>{$file}</a>";
        echo "</li>";
    }
    echo "</ul>";
});

Route::get('doc/{file}', function($route) {
    $fm = new FileManager(HOME_PATH . 'docs', false);
    $parser = new MarkdownExtra();
    $markdown = $fm->loadFile($route->params['file']);
    echo $parser->parse($markdown);
});

Route::get('db', function(){
    $a = Chest::qb()->table('book')->get();
    foreach ($a as $item) {
        $item = \Models\Book::cast($item);
        d($item);
    }
//    var_dump(Chest::simpleInsert('book', ['title' => 'lalala'.rand(1,999), 'author_name' => 'blaster']));
//    var_dump(Chest::simpleUpdate('book', ['title' => 'wololo'], ['title'], 7));
//    var_dump(Chest::simpleDelete('book', 7));
//    var_dump(Chest::count('book', 'lalala', 'title'));
//    var_dump(Chest::simpleGet('book', ['title'], 1));
//    $obj = new \Models\Book();
//    $obj->title = 'livro com autor';
//    $obj->authorName = "Maycow";
//    $obj->save();
//    d($obj);
//    $obj = \Models\Book::find(14);
//    $obj->authorName = "Maycow Antunes";
//    $obj->save();
//    var_dump($obj);
//    $obj->title = 'teste';
//    dd($obj);
//    var_dump($obj->update());
//    $obj = \Models\Book::factory();
//    $obj->title = 'insert'. rand(1,999);
//    var_dump($obj->insert());
//    $obj = \Models\Book::factory();
//    $obj->id = 10;
//    var_dump($obj->delete());
//    var_dump(Chest::query("select title from book where id = ?", [1]));
//    var_dump(Chest::execute("update book set title = 'woooow' where id = ?", [1]));
//
    if(count(Chest::getInstance()->errors) > 0) {
        ops(Chest::getInstance()->errors[0]);
    }
    var_dump(Chest::getInstance()->errors);
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
