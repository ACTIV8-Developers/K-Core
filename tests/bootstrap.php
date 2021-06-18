<?php
ob_start();
/*
|--------------------------------------------------------------------------
| Register the composer auto loader
|--------------------------------------------------------------------------
*/
require __DIR__.'/../vendor/autoload.php';
/*
|--------------------------------------------------------------------------
| Register timezone
|--------------------------------------------------------------------------
*/
date_default_timezone_set('UTC');
/*
|--------------------------------------------------------------------------
| Mock up request
|--------------------------------------------------------------------------
*/
\Core\Routing\Router::$CONTROLLERS_ROOT = '';

$_SERVER = [
    'SERVER_PROTOCOL'      => 'HTTP/1.1',
    'REQUEST_METHOD'       => 'GET',
    'SCRIPT_NAME'          => '/www/index.php',
    'REQUEST_URI'          => '/test/2',
    'QUERY_STRING'         => '',
    'SERVER_NAME'          => 'localhost',
    'SERVER_PORT'          => 80,
    'HTTP_HOST'            => 'localhost',
    'HTTP_ACCEPT'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    'HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.8',
    'HTTP_ACCEPT_CHARSET'  => 'ISO-8859-1,utf-8;q=0.7,*;q=0.3',
    'HTTP_USER_AGENT'      => 'User agent',
    'REMOTE_ADDR'          => '127.0.0.1',
    'REQUEST_TIME'         => time(),
    'REQUEST_TIME_FLOAT'   => microtime(true),
];