<?php
// Test route
$route->get('test/:id', 'TestController', 'index')
    ->executeBefore(new TestController())
    ->executeAfter(new TestController());