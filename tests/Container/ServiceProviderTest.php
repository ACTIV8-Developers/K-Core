<?php

class ServiceProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \Core\Container\ServiceProvider::register()
     */
    public function testRegister()
    {
        $stub = $this->getMockForAbstractClass('Core\Container\ServiceProvider');

        $stub->register();

        $service = new TestServiceClass();

        $this->assertEquals('test', $service->register());
    }
}

class TestServiceClass extends \Core\Container\ServiceProvider
{
    public function register()
    {
        return 'test';
    }
}