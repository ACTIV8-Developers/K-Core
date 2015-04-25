<?php

class AliasLoaderTest extends PHPUnit_Framework_TestCase
{
    public function testLoaderCanBeCreatedAndRegisteredOnce()
    {
        $loader = \Core\Util\AliasLoader::getInstance(array('foo' => 'bar'));

        $this->assertEquals(array('foo' => 'bar'), $loader->getAliases());
        $this->assertFalse($loader->isRegistered());
        $loader->register();

        $this->assertTrue($loader->isRegistered());
    }

    public function testGetInstanceCreatesOneInstance()
    {
        $loader = \Core\Util\AliasLoader::getInstance(array('foo' => 'bar'));
        $this->assertEquals($loader, \Core\Util\AliasLoader::getInstance());
    }
}