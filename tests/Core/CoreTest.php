<?php

class CoreTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        // Make instance of app.
        $app = Core\Core\Core::getNew();

        // Check if construct made all required things.
        $this->assertInstanceOf('Core\Core\Core', $app);
        $this->assertInstanceOf('Core\Http\Response', $app['response']);
        $this->assertInstanceOf('Core\Http\Request', $app['request']);
        $this->assertInstanceOf('Core\Session\Session', $app['session']);
    }

    public function testRouteRequest()
    {
        // Make instance of app.
        $app = Core\Core\Core::getNew();      

        $this->expectOutputString('<p>Working</p>');
        $app->routeRequest();
    }

    public function testSendReponse()
    {
        // Make instance of app.
        $app = Core\Core\Core::getNew();

        $app['response']->setContent('<div>Test</div>');

        $this->expectOutputString('<div>Test</div>');
        $app->sendResponse();
    }

    public function testNotFound()
    {
        // Make instance of app.
        $app = Core\Core\Core::getNew();

        $ex = new \Core\Core\NotFoundException('Test');

        $app->notFound($ex);

        $this->assertEquals($app['response']->getContent(), '<h1>404 Not Found</h1>The page that you have requested could not be found.');
    }
    
    public function testHooks()
    {
        // Make instance of app.
        $app = Core\Core\Core::getNew();

        // Make some functions.
        $function1 = function($app) {
            $app['foo'] = 'bar';
        };

        $function2 = function($app) {
            $app['bar'] = 'foo';
        };

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