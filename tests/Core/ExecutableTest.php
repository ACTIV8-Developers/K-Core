<?php

class ExecutableTest extends PHPUnit_Framework_TestCase
{
    public function testGettersSetters()
    {
        $e = new \Core\Core\Executable('class', 'method', ['test']);

        $container = new \Core\Container\Container();
        $container['foo'] = 'bar';

        $e->setApp($container);

        $this->assertEquals('class', $e->getClass());

        $this->assertEquals('method', $e->getMethod());

        $this->assertEquals(['test'], $e->getParams());

        $e->setClass('TestClass');

        $e->setMethod('getFoo');

        $e->setParams(['foo']);

        $e->execute();
    }
}

class TestClass extends \Core\Container\ContainerAware
{
    public function getFoo()
    {
        return $this->foo;
    }
}