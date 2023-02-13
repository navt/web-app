<?php

chdir(__DIR__);

spl_autoload_register(function (string $class) {
    $path = sprintf("php-lib/%s.php", $class);
    if (file_exists($path)) {
        include $path;
    }
});

$box = new Box('php-lib/values.ini');
$router = $box['router'];

$router->map('GET', '/', function() {
    $content = 'posts';
    require 'views/all.php';
}, 'all_records');
$router->map('GET', '/posts/[i:id]', function($id) {
    require 'views/one.php';
}, 'one_record');
$router->map('GET', '/posts/[i:from]-[i:to]', function($from, $to) {
    require 'views/all.php';
}, 'some_records');
$router->map('GET', '/edit/0', function() {
    require 'views/editList.php';
}, 'edit_list');
$router->map('GET', '/add', function() {
    require 'views/add.php';
}, 'add_post');
$router->map('GET', '/edit/[i:id]', function($id) {
    require 'views/edit.php';
}, 'edit_record');
$router->map('GET', '/auth', function() {
    require 'views/auth.php';
}, 'auth');

$match = $router->match();

// call closure or throw 404 status
if( is_array($match) && is_callable( $match['target'] ) ) {
	call_user_func_array( $match['target'], $match['params'] ); 
} else {
	// no route was matched
	header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
}