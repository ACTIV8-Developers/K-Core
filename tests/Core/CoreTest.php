<?php

class CoreTest extends PHPUnit_Framework_TestCase
{
    public static $test = '';

    public function testBoot()
    {
        // Make instance of app.
        $app = \Core\Core\Core::getNew(__DIR__ . '/../MockApp');

        $app->boot();

        // Check if construct made all required things.
        $this->assertInstanceOf('Core\Core\Core', $app);
        $this->assertInstanceOf('Core\Http\Response', $app['response']);
        $this->assertInstanceOf('Core\Http\Request', $app['request']);
        $this->assertInstanceOf('Core\Session\Session', $app['session']);
    }

    public function testRun()
    {
        // Make instance of app.
        $app = \Core\Core\Core::getNew(__DIR__ . '/../MockApp');

        $app->boot();

        $app->run();

        $this->assertEquals(self::$test, 'test');
    }

    public function testSendResponse()
    {
        // Make instance of app.
        $app = \Core\Core\Core::getNew(__DIR__ . '/../MockApp')->boot();

        $app['response']->setBody('<div>Test</div>');

        //$this->expectOutputString('<div>Test</div>');

        $app->sendResponse();
    }
    
    public function testHooks()
    {
        // Make instance of app.
        $app = \Core\Core\Core::getNew(__DIR__ . '/../MockApp');

        $app->boot();

        // Make hooks.
        $app->setHook('before.routing', 'Foo', 'bar');
        $app->setHook('after.routing', 'Bar', 'foo');

        // Test hooks.
        $this->assertTrue($app->getHook('before.routing') instanceof \Core\Routing\Interfaces\ExecutableInterface);
        $this->assertTrue($app->getHook('after.routing')instanceof \Core\Routing\Interfaces\ExecutableInterface);
    }
}

class TestController
{
    public function index()
    {
        CoreTest::$test = 'test';
    }
}