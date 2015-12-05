<?php

use Core\Container\ServiceProvider;

class ServiceProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers TestServiceClass::register
     */
    public function testRegister()
    {
        $stub = $this->getMockForAbstractClass('Core\Container\ServiceProvider');

        $stub->expects($this->any())
            ->method('register');

        $stub->register();

        $service = new TestServiceClass();

        $this->assertEquals('test', $service->register());
    }
}

class TestServiceClass extends ServiceProvider
{
    public function register()
    {
        return 'test';
    }
}