<?php

class ContainerAwareTest extends \PHPUnit\Framework\TestCase
{
    public function testGetSet()
    {
        $c = new AnotherContainer;

        $container = new \Core\Container\Container(__DIR__ . '/../MockApp');
        $container['foo'] = 'bar';

        $c->setContainer($container);

        $this->assertEquals($c->getValue(), 'bar');

        $this->assertEquals($c->getValue2(), 'bar');
    }

    public function testInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $c = new AnotherContainer;

        $container = new \Core\Container\Container(__DIR__ . '/../MockApp');
        $container['foo'] = 'bar';

        $c->setContainer($container);

        $c->getUknown();
    }


    public function testInvalidArgument2()
    {
        $this->expectException(InvalidArgumentException::class);
        $c = new AnotherContainer;

        $container = new \Core\Container\Container(__DIR__ . '/../MockApp');
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