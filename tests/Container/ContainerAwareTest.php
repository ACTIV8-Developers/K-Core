<?php

class ContainerAwareTest extends PHPUnit_Framework_TestCase
{
    public function testGetSet()
    {
        $c = new AnotherContainer;

        $container = new \Core\Container\Container();
        $container['foo'] = 'bar';

        $c->setContainer($container);

        $this->assertEquals($c->getValue(), 'bar');

        $this->assertEquals($c->getValue2(), 'bar');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArgument()
    {
        $c = new AnotherContainer;

        $container = new \Core\Container\Container();
        $container['foo'] = 'bar';

        $c->setContainer($container);

        $c->getUknown();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArgument2()
    {
        $c = new AnotherContainer;

        $container = new \Core\Container\Container();
        $container['foo'] = 'bar';

        $c->setContainer($container);

        $c->getUknown2();
    }
}

class AnotherContainer extends \Core\Container\ContainerAware
{
    public function getValue()
    {
        return $this->container->get('foo');
    }

    public function getValue2()
    {
        return $this->foo;
    }

    public function getUknown()
    {
        return $this->container->get('uknown');
    }

    public function getUknown2()
    {
        return $this->uknown;
    }
}