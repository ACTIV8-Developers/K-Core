<?php

class CoreTest extends PHPUnit_Framework_TestCase
{
    public static $test = '';

    public static $hookCounter = 0;

    public function testCustomConstruct()
    {
        $container = new \Core\Container\Container();
        $resolver = new \Core\Core\Resolver($container);
        $core = new \Core\Core\Core('', $container, $resolver);
    }

    public function testConstruct()
    {
        \Core\Core\Core::setInstance(null);

        $app = \Core\Core\Core::getInstance(__DIR__ . '/../MockApp');

        \Core\Routing\Router::$CONTROLLERS_ROOT = '';

        $app->setAppPath('appPath');
        $this->assertEquals('appPath', $app->getAppPath());

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
        $this->assertInstanceOf('Core\Http\Response', $app->getContainer()['response']);
        $this->assertInstanceOf('Core\Http\Request', $app->getContainer()['request']);
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

        $app->getContainer()['response']->setBody('<div>Test</div>');

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
        $app->setHook('before.routing', 'TestHook', 'hook');
        $app->setHook('after.routing', 'TestHook', 'hook');
        $app->setHook('after.response', 'TestHook', 'hook');

        // Test hooks.
        $this->assertTrue($app->getHook('before.boot') instanceof \Core\Routing\Interfaces\ExecutableInterface);
        $this->assertTrue($app->getHook('after.boot') instanceof \Core\Routing\Interfaces\ExecutableInterface);
        $this->assertTrue($app->getHook('before.routing') instanceof \Core\Routing\Interfaces\ExecutableInterface);
        $this->assertTrue($app->getHook('after.routing') instanceof \Core\Routing\Interfaces\ExecutableInterface);
        $this->assertTrue($app->getHook('after.response') instanceof \Core\Routing\Interfaces\ExecutableInterface);

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

        $app->getContainer()['request']->setUri('uknown');

        $app->execute();
    }

    public function testControllerNotFound()
    {
        $app = \Core\Core\Core::getNew(__DIR__ . '/../MockApp');

        $app->boot();

        $app->getContainer()['request']->setUri('notfound');

        $app->getContainer()['router']->addRoute(new \Core\Routing\Route('notfound','GET','TestController', 'notFound'));

        $app->execute();

        $this->assertEquals('Not found', $app->getContainer()['response']->getBody());
    }

    public function testNotFoundHook()
    {
        $app = \Core\Core\Core::getNew(__DIR__ . '/../MockApp');

        $app->setHook('not.found', 'TestHook', 'notFound');

        $app->boot();

        $app->getContainer()['request']->setUri('uknown');

        $app->execute();
    }

    public function testControllerException()
    {
        $app = \Core\Core\Core::getNew(__DIR__ . '/../MockApp');

        $app->boot();

        $app->getContainer()['request']->setUri('error');

        $app->getContainer()['router']->addRoute(new \Core\Routing\Route('error','GET','TestController', 'error'));

        $app->execute();

        $this->assertEquals('Internal error: ' . 'Error', $app->getContainer()['response']->getBody());
    }

    public function testControllerExceptionHook()
    {
        $app = \Core\Core\Core::getNew(__DIR__ . '/../MockApp');

        $app->setHook('internal.error', 'TestHook', 'error');

        $app->boot();

        $app->getContainer()['request']->setUri('error');

        $app->getContainer()['router']->addRoute(new \Core\Routing\Route('error','GET','TestController', 'error'));

        $app->execute();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testBadHook()
    {
        $app = \Core\Core\Core::getNew(__DIR__ . '/../MockApp');

        $app->setHook('not.found', new TestController());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testBadMiddleware()
    {
        $app = \Core\Core\Core::getNew(__DIR__ . '/../MockApp');

        $app->addMiddleware([]);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidServiceProvider()
    {
        $core = new \Core\Core\Core(__DIR__ . '/../MockApp');

        $core->addService('TestServiceProvider');

        $core->addService('TestInvalidServiceProvider');

        $core->boot();
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

class TestServiceProvider extends \Core\Container\ServiceProvider
{
    /**
     * Register the service provider(s).
     */
    public function register()
    {

    }
}

class TestInvalidServiceProvider
{
    /**
     * Register the service provider(s).
     */
    public function register()
    {

    }
}