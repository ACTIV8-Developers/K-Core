<?php

class CoreTest extends PHPUnit_Framework_TestCase
{
    public static $test = '';

    public static $hookCounter = 0;

    public function testConstruct()
    {
        \Core\Core\Core::setInstance(null);

        $app = \Core\Core\Core::getInstance(__DIR__ . '/../MockApp');

        $app->setViewsPath('viewsPath');
        $this->assertEquals('viewsPath', $app->getViewsPath());

        $app->setConfigPath('configPath');
        $this->assertEquals('configPath', $app->getConfigPath());

        $app->setAppPath('appPath');
        $this->assertEquals('appPath', $app->getAppPath());

        $app->setNamespacePrefix('namespace');
        $this->assertEquals('namespace'. '\\', $app->getNamespacePrefix());

        $app->setRoutesPath('routes');

        $app->boot();

        $app->setInstance(null);
    }

    public function testBoot()
    {
        // Make instance of app.
        $app = \Core\Core\Core::getNew(__DIR__ . '/../MockApp');

        $app->boot();

        // Check if construct made all required things.
        $this->assertInstanceOf('Core\Core\Core', $app);
        $this->assertInstanceOf('Core\Http\Response', $app['response']);
        $this->assertInstanceOf('Core\Http\Request', $app['request']);
    }

    public function testExecute()
    {
        // Make instance of app.
        $app = \Core\Core\Core::getNew(__DIR__ . '/../MockApp');

        $app->boot();

        $app->execute();

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

        // Make hooks.
        $app->setHook('before.boot', 'TestHook', 'hook');
        $app->setHook('after.boot', 'TestHook', 'hook');
        $app->setHook('before.run', 'TestHook', 'hook');
        $app->setHook('after.run', 'TestHook', 'hook');
        $app->setHook('after.response', 'TestHook', 'hook');

        // Test hooks.
        $this->assertTrue($app->getHook('before.boot') instanceof \Core\Core\Interfaces\ExecutableInterface);
        $this->assertTrue($app->getHook('after.boot') instanceof \Core\Core\Interfaces\ExecutableInterface);
        $this->assertTrue($app->getHook('before.run') instanceof \Core\Core\Interfaces\ExecutableInterface);
        $this->assertTrue($app->getHook('after.run') instanceof \Core\Core\Interfaces\ExecutableInterface);
        $this->assertTrue($app->getHook('after.response') instanceof \Core\Core\Interfaces\ExecutableInterface);

        $app->boot()->execute()->sendResponse();

        $this->assertEquals(5, self::$hookCounter);
    }

    public function testMiddleware()
    {
        $app = \Core\Core\Core::getNew(__DIR__ . '/../MockApp');

        $app->addMiddleware('TestMiddleware');

        $app->boot();

        $app->execute();
    }

    public function testService()
    {
        $app = \Core\Core\Core::getNew(__DIR__ . '/../MockApp');

        $app->addService('TestService');

        $app->boot();

        $app->execute();
    }

    /**
     * @expectedException BadFunctionCallException
     */
    public function testNotBooted()
    {
        $app = \Core\Core\Core::getNew(__DIR__ . '/../MockApp');

        $app->execute();
    }

    /**
     * @expectedException BadFunctionCallException
     */
    public function testNotBooted2()
    {
        $app = \Core\Core\Core::getNew(__DIR__ . '/../MockApp');

        $app->sendResponse();
    }

    public function testNotFound()
    {
        $app = \Core\Core\Core::getNew(__DIR__ . '/../MockApp');

        $app->boot();

        $app['request']->setUri('uknown');

        $app->execute();
    }

    public function testControllerNotFound()
    {
        $app = \Core\Core\Core::getNew(__DIR__ . '/../MockApp');

        $app->boot();

        $app['request']->setUri('notfound');

        $app['router']->addRoute(new \Core\Routing\Route('notfound','GET','TestController', 'notFound'));

        $app->execute();

        $this->assertEquals('Not found', $app['response']->getBody());
    }

    public function testNotFoundHook()
    {
        $app = \Core\Core\Core::getNew(__DIR__ . '/../MockApp');

        $app->setHook('not.found', 'TestHook', 'notFound');

        $app->boot();

        $app['request']->setUri('uknown');

        $app->execute();
    }

    public function testControllerException()
    {
        $app = \Core\Core\Core::getNew(__DIR__ . '/../MockApp');

        $app->boot();

        $app['request']->setUri('error');

        $app['router']->addRoute(new \Core\Routing\Route('error','GET','TestController', 'error'));

        $app->execute();

        $this->assertEquals('Internal error: ' . 'Error', $app['response']->getBody());
    }

    public function testControllerExceptionHook()
    {
        $app = \Core\Core\Core::getNew(__DIR__ . '/../MockApp');

        $app->setHook('internal.error', 'TestHook', 'error');

        $app->boot();

        $app['request']->setUri('error');

        $app['router']->addRoute(new \Core\Routing\Route('error','GET','TestController', 'error'));

        $app->execute();
    }
}

class TestController
{
    public function index()
    {
        CoreTest::$test = 'test';
    }

    public function notFound()
    {
        throw new \Core\Core\Exceptions\NotFoundException('Not found');
    }

    public function error()
    {
        throw new Exception('Error');
    }
}

class TestHook
{
    public function hook()
    {
        CoreTest::$hookCounter++;
    }

    public function notFound()
    {

    }

    public function error()
    {

    }
}

class TestService extends \Core\Container\ServiceProvider
{
    public function register()
    {
        // Register some service
    }
}

class TestMiddleware
{
    public function execute()
    {
        // Execute middleware
    }
}