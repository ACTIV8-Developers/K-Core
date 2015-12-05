<?php
// Test route
$route->get('test/:id', 'TestController', 'index')->executeAfter('TestController', 'index')->executeBefore('TestController', 'index');