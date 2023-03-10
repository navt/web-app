<?php
// API routs
//
// $ cd web-app/web
// $ php -S localhost:8000
// try in browser http://localhost:8000/api/posts/1-3

chdir(dirname(__DIR__));

spl_autoload_register(function (string $class) {
    $path = sprintf("php-lib/%s.php", $class);
    if (file_exists($path)) {
        include $path;
    }
});

$availableMethods = ['GET','POST','PUT','DELETE'];

$server = (object)$_SERVER;
$method = $server->REQUEST_METHOD;

if (in_array($method, $availableMethods) === false) {
    Reply::giveJson([["error" => "method {method} not supported"]]);
    exit();
}

$box = new Box('php-lib/values.ini');

$router = $box['router'];
$reply = $box['reply'];


$router->setBasePath('/api');
// GET requests
$router->map('GET', '/[a:content]/0', function($content) use($reply) {
        $reply->all($content);
    }, 'all_records');
$router->map('GET', '/[a:content]/[i:id]', function($content, $id) use($reply) {
        $reply->one($content, $id);
    }, 'one_record');
$router->map('GET', '/items/[i:from]-[i:to]', function($from, $to) use($reply) {
        $reply->someAuth($from, $to);
    }, 'some_auth');
$router->map('GET', '/posts/[i:from]-[i:to]', function($from, $to) use($reply) {
        $reply->someFree($from, $to);
    }, 'some_free');
// other requests
$router->map('POST', '/add', function() use($reply) {
    $content = 'posts';
    $reply->add($content);
}, 'add_record');
$router->map('POST', '/edit', function() use($reply) {
    $content = 'posts';
    $reply->update($content);
}, 'edit_record');
$router->map('POST', '/delete', function() use($reply) {
    $reply->delete();
}, 'delete_record');
$router->map('POST', '/auth', function() use($reply) {
    $reply->auth();
}, 'auth');

$match = $router->match();

// call closure or report error
if( is_array($match) && is_callable( $match['target'] ) ) {
    call_user_func_array( $match['target'], $match['params'] ); 
} else {
	// no route was matched
    $uri = $server->REQUEST_URI;
    H::giveJson(
        [["error" => "no relevant api reqest for method: {$method} uri: {$uri}"]]
    );
}