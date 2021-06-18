<?php

class ResolverTest extends \PHPUnit\Framework\TestCase
{
    public function testGetSet()
    {
        $container = new \Core\Container\Container(__DIR__ . '/../MockApp');

        $container['AnotherTestClass'] = function($c) {
            return new AnotherTestClass();
        };

        $resolver = new \Core\Core\Resolver($container);

        $t1 = $resolver->resolve('AnotherTestClass');

        $t2 = $resolver->resolve('AnotherTestClass');

        $this->assertEquals($t1, $t2);
    }
}

class AnotherTestClass
{
    private $foo = 'bar';

    public function getFoo()
    {
        return $this->foo;
    }
}