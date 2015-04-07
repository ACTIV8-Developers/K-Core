<?php

class CoreTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        // Make instance of app.
        $app = \Core\Core\Core::getInstance();

        // Check if construct made all required things.
        $this->assertInstanceOf('Core\Core\Core', $app);
        $this->assertInstanceOf('Core\Http\Response', $app['response']);
        $this->assertInstanceOf('Core\Http\Request', $app['request']);
        $this->assertInstanceOf('Core\Session\Session', $app['session']);
    }

    public function testRouteRequest()
    {
        // Make instance of app.
        $app = \Core\Core\Core::getInstance();      

        $this->expectOutputString('<p>Working</p>');
        $app->run();

        $app->sendResponse();
    }

    public function testSendResponse()
    {
        // Make instance of app.
        $app = \Core\Core\Core::getInstance();

        $app['response']->setBody('<div>Test</div>');

        $this->expectOutputString('<div>Test</div>');
        $app->sendResponse();
    }
    
    public function testHooks()
    {
        // Make instance of app.
        $app = \Core\Core\Core::getInstance();

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
        echo '<p>Working</p>';
    }
}