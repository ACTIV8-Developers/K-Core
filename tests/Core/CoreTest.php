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
    }

    public function testSendReponse()
    {
        // Make instance of app.
        $app = \Core\Core\Core::getInstance();

        $app['response']->setBody('<div>Test</div>');

        //$this->expectOutputString('<div>Test</div>');
        $app->sendResponse();
    }
    
    public function testHooks()
    {
        // Make instance of app.
        $app = \Core\Core\Core::getInstance();

        // Make some functions.
        $function1 = ['Foo', 'bar'];

        $function2 = ['Bar', 'foo'];

        // Make hooks.
        $app->setHook('before.routing', $function1);
        $app->setHook('after.routing', $function2);

        // Test hooks.
        $this->assertEquals($app->getHook('before.routing'), $function1);
        $this->assertEquals($app->getHook('after.routing'), $function2);
    }
}

class TestController
{
    public function index()
    {
        echo '<p>Working</p>';
    }
}