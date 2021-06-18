<?php
// Test route
use Core\Middleware\CorsMiddleware;

$route->get('test/:id', 'TestController', 'index')
    ->addMiddleware(function($next) {
        $next();
    })
    ->addMiddleware(function($next) {
        $next();
    })
    ->addMiddleware(function($next) {
        $next();
    })
    ->addMiddleware(function($next) {
        $next();
    })
    ->addMiddleware(function($next) {
        $next();
    })
    ->addMiddleware(function($next) {
        $next();
    });

;
/*
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
*/
