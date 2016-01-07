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
        $app = new \Core\Core\Core(__DIR__ . '/../MockApp');

        \Core\Routing\Router::$CONTROLLERS_ROOT = '';

        $app->setAppPath('appPath');
        $this->assertEquals('appPath', $app->getAppPath());
    }

    public function testBoot()
    {
        // Make instance of app.
        $app = new \Core\Core\Core(__DIR__ . '/../MockApp');;

        // Check if construct made all required things.
        $this->assertInstanceOf('Core\Core\Core', $app);
        $this->assertInstanceOf('Core\Http\Response', $app->getContainer()['response']);
        $this->assertInstanceOf('Core\Http\Request', $app->getContainer()['request']);
    }

    public function testExecute()
    {
        // Make instance of app.
        $app = new \Core\Core\Core(__DIR__ . '/../MockApp');;

        $app->execute();

        $this->assertEquals(self::$test, 'test');
    }

    public function testSendResponse()
    {
        // Make instance of app.
        $app = new \Core\Core\Core(__DIR__ . '/../MockApp');

        $app->getContainer()['response']->setBody('<div>Test</div>');

        //$this->expectOutputString('<div>Test</div>');

        $app->sendResponse();
    }
    
    public function testHooks()
    {
        // Make instance of app.
        $app = new \Core\Core\Core(__DIR__ . '/../MockApp');

        // Make hooks.
        $app->setHook('before.execute', 'TestHook', 'hook');
        $app->setHook('after.execute', 'TestHook', 'hook');
        $app->setHook('before.routing', 'TestHook', 'hook');
        $app->setHook('after.routing', 'TestHook', 'hook');
        $app->setHook('after.response', 'TestHook', 'hook');

        // Test hooks.
        $this->assertTrue($app->getHook('before.execute') instanceof \Core\Routing\Interfaces\ExecutableInterface);
        $this->assertTrue($app->getHook('after.execute') instanceof \Core\Routing\Interfaces\ExecutableInterface);
        $this->assertTrue($app->getHook('before.routing') instanceof \Core\Routing\Interfaces\ExecutableInterface);
        $this->assertTrue($app->getHook('after.routing') instanceof \Core\Routing\Interfaces\ExecutableInterface);
        $this->assertTrue($app->getHook('after.response') instanceof \Core\Routing\Interfaces\ExecutableInterface);

        $app->execute()->sendResponse();

        $this->assertEquals(5, self::$hookCounter);
    }

    public function testMiddleware()
    {
        $app = new \Core\Core\Core(__DIR__ . '/../MockApp');

        $app->addMiddleware('TestMiddleware');;

        $app->execute();
    }

    public function testNotFound()
    {
        $app = new \Core\Core\Core(__DIR__ . '/../MockApp');

        $app->getContainer()['request']->setUri('uknown');

        $app->execute();
    }

    public function testControllerNotFound()
    {
        $app = new \Core\Core\Core(__DIR__ . '/../MockApp');

        $app->getContainer()['request']->setUri('notfound');

        $app->getContainer()['router']->addRoute(new \Core\Routing\Route('notfound','GET','TestController', 'notFound'));

        $app->execute();

        $this->assertEquals('Not found', $app->getContainer()['response']->getBody());
    }

    public function testNotFoundHook()
    {
        $app = new \Core\Core\Core(__DIR__ . '/../MockApp');

        $app->setHook('not.found', 'TestHook', 'notFound');

        $app->getContainer()['request']->setUri('uknown');

        $app->execute();
    }

    public function testControllerException()
    {
        $app = new \Core\Core\Core(__DIR__ . '/../MockApp');

        $app->getContainer()['request']->setUri('error');

        $app->getContainer()['router']->addRoute(new \Core\Routing\Route('error','GET','TestController', 'error'));

        $app->execute();

        $this->assertEquals('Internal error: ' . 'Error', $app->getContainer()['response']->getBody());
    }

    public function testControllerExceptionHook()
    {
        $app = new \Core\Core\Core(__DIR__ . '/../MockApp');

        $app->setHook('internal.error', 'TestHook', 'error');

        $app->getContainer()['request']->setUri('error');

        $app->getContainer()['router']->addRoute(new \Core\Routing\Route('error','GET','TestController', 'error'));

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

class TestMiddleware
{
    public function execute()
    {
        // Execute middleware
    }
}