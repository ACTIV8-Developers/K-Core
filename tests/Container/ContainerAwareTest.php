<?php

class ContainerAwareTest extends PHPUnit_Framework_TestCase
{
    public function testContainer()
    {
        $container = new \Core\Container\Container();
        $container['foo'] = 'bar';

        $c = new TestContainer();
        $c->setApp($container);

        $this->assertEquals('bar', $c->getFoo());

        $this->assertEquals($container, $c->getApp());

        $this->assertEquals('bar', $c->getFoo2());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArgument()
    {
        $container = new \Core\Container\Container();
        $container['foo'] = 'bar';

        $c = new TestContainer();
        $c->setApp($container);

        $c->getUknown();
    }
}

class TestContainer extends \Core\Container\ContainerAware
{
    public function getFoo()
    {
        return $this->foo;
    }

    public function getFoo2()
    {
        return $this->app['foo'];
    }

    public function getUknown()
    {
        return $this->app['uknown'];
    }
}