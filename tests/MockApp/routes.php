<?php
// Test route
$route->get('test/:id', 'TestController', 'index')
    ->addMiddleware(new TestController())
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

$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());
$route->get('nothere/:id', 'TestController', 'index')
    ->addMiddleware(new TestController());

