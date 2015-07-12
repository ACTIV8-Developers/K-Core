<?php

class ExecutableTest extends PHPUnit_Framework_TestCase
{
    public function testGettersSetters()
    {
        $e = new \Core\Routing\Executable('TestClass', 'getFoo', ['test']);

        $e->execute();
    }
}

class TestClass extends \Core\Core\Controller
{
    public function getFoo()
    {
        return 'test';
    }
}