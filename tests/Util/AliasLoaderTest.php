<?php

class AliasLoaderTest extends \PHPUnit\Framework\TestCase
{
    public function testLoaderCanBeCreatedAndRegisteredOnce()
    {
        $loader = \Core\Util\AliasLoader::getInstance(array('foo' => 'bar'));

        $this->assertEquals(array('foo' => 'bar'), $loader->getAliases());
        $this->assertFalse($loader->isRegistered());
        $loader->register();

        $this->assertTrue($loader->isRegistered());

        $loader->setRegistered(true);

        $this->assertTrue($loader->isRegistered());
    }

    public function testGetInstanceCreatesOneInstance()
    {
        $loader = \Core\Util\AliasLoader::getInstance(array('foo' => 'bar'));
        $this->assertEquals($loader, \Core\Util\AliasLoader::getInstance());

        \Core\Util\AliasLoader::setInstance($loader);
        $this->assertEquals($loader, \Core\Util\AliasLoader::getInstance());
    }

    public function testLoader()
    {
        \Core\Util\AliasLoader::getInstance()->alias('Util', 'Core\Util\Util');
        ;
        $this->assertEquals(Util::base(''), Util::base(''));
    }
}