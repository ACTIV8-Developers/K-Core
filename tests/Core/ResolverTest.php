<?php

class ResolverTest extends PHPUnit_Framework_TestCase
{
    public function testGetSet()
    {
        $container = new \Core\Container\Container();

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