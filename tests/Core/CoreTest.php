<?php

use \Core\Container\Container;

class CoreTest extends PHPUnit_Framework_TestCase
{
    public static $test = '';

    public static $hookCounter = 0;

    public static $middlewareStack = '';

    public function testCustomConstruct()
    {
        $container = new \Core\Container\Container(__DIR__ . '/../MockApp');
        $resolver = new \Core\Core\Resolver($container);
        $app = new \Core\Core\Core($container, $resolver);

        $this->assertEquals($container, $app->getContainer());
    }

    public function testConstruct()
    {
        $app = new \Core\Core\Core(new Container(__DIR__ . '/../MockApp'));

        \Core\Routing\Router::$CONTROLLERS_ROOT = '';

        // Check if construct made all required things.
        $this->assertInstanceOf('Core\Core\Core', $app);
    }

    public function testExecute()
    {
        // Make instance of app.
        $app = new \Core\Core\Core(new Container(__DIR__ . '/../MockApp'));;

        $app->execute();

        $this->assertEquals(self::$test, 'testtest');
    }

    public function testSendResponse()
    {
        // Make instance of app.
        $app = new \Core\Core\Core(new Container(__DIR__ . '/../MockApp'));

        $app->getContainer()['response']->writeBody('<div>Test</div>');

        $app->sendResponse();
    }
    
    public function testHooks()
    {
        // Make instance of app.
        $app = new \Core\Core\Core(new Container(__DIR__ . '/../MockApp'));

        // Make hooks.
        $app->setHook('before.execute', new TestHook());
        $app->setHook('before.routing', new TestHook());
        $app->setHook('after.response', new TestHook());

        // Test hooks.
        $this->assertTrue(is_callable($app->getHook('before.execute') ));
        $this->assertTrue(is_callable($app->getHook('before.routing') ));
        $this->assertTrue(is_callable($app->getHook('after.response') ));

        $app->execute()->sendResponse();

        $this->assertEquals(3, self::$hookCounter);

        $this->assertEquals(self::$test, 'testtesttesttest');
    }

    public function testMiddleware()
    {
        $app = new \Core\Core\Core(new Container(__DIR__ . '/../MockApp'));

        $app->addMiddleware(new TestMiddleware3());
        $app->addMiddleware(new TestMiddleware2());
        $app->addMiddleware(new TestMiddleware1());

        $app->execute();

        $this->assertEquals('123', self::$middlewareStack);

        $this->assertEquals(self::$test, 'testtesttesttesttesttest');
    }

    public function testNotFound()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/notfound';

        $app = new \Core\Core\Core(new Container(__DIR__ . '/../MockApp'));

        $app->execute();
    }

    public function testControllerNotFound()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/notfound';

        $app = new \Core\Core\Core(new Container(__DIR__ . '/../MockApp'));

        $app->getContainer()['router']->addRoute(new \Core\Routing\Route('notfound', 'GET', 'TestController', 'notFound'));

        $app->execute();

        $this->assertEquals('Not found', (string)$app->getContainer()['response']->getBody());
    }

    public function testNotFoundHook()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/notfound';

        $app = new \Core\Core\Core(new Container(__DIR__ . '/../MockApp'));

        $app->setHook('not.found', function() {
                (new TestHook())->notFound();
            }
        );

        $app->execute();
    }

    public function testControllerException()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/error';

        $app = new \Core\Core\Core(new Container(__DIR__ . '/../MockApp'));

        $app->getContainer()['router']->addRoute(new \Core\Routing\Route('error', 'GET','TestController', 'error'));

        $app->execute();

        $this->assertEquals('Internal error: ' . 'Error', (string)$app->getContainer()['response']->getBody());
    }

    public function testControllerExceptionHook()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/error';

        $app = new \Core\Core\Core(new Container(__DIR__ . '/../MockApp'));

        $app->setHook('internal.error', function() {
                (new TestHook())->error();
            }
        );

        $app->getContainer()['router']->addRoute(new \Core\Routing\Route('error','GET','TestController', 'error'));

        $app->execute();
    }
}

class TestController
{
    public function index()
    {
        CoreTest::$test .= 'test';
    }

    public function notFound()
    {
        throw new \Core\Core\Exceptions\NotFoundException('Not found');
    }

    public function error()
    {
        throw new Exception('Error');
    }

    public function __invoke($next)
    {
        $this->index();

        $next();
    }
}

class TestHook
{
    public function index()
    {
        CoreTest::$hookCounter++;
    }

    public function notFound()
    {

    }

    public function error()
    {

    }

    public function __invoke()
    {
        $this->index();
    }
}

class TestMiddleware1
{
    public function __invoke($next)
    {
        CoreTest::$middlewareStack .= '1';

        $next();
    }
}

class TestMiddleware2
{
    public function __invoke($next)
    {
        CoreTest::$middlewareStack .= '2';

        $next();
    }
}

class TestMiddleware3
{
    public function __invoke($next)
    {
        CoreTest::$middlewareStack .= '3';

        $next();
    }
}