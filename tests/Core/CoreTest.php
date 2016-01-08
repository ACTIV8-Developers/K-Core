<?php

class CoreTest extends PHPUnit_Framework_TestCase
{
    public static $test = '';

    public static $hookCounter = 0;

    public static $middlewareStack = '';

    public function testCustomConstruct()
    {
        $container = new \Core\Container\Container();
        $resolver = new \Core\Core\Resolver($container);
        $app = new \Core\Core\Core('', $container, $resolver);

        $this->assertEquals($container, $app->getContainer());
    }

    public function testConstruct()
    {
        $app = new \Core\Core\Core(__DIR__ . '/../MockApp');

        \Core\Routing\Router::$CONTROLLERS_ROOT = '';

        $app->setAppPath('appPath');
        $this->assertEquals('appPath', $app->getAppPath());

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

        $this->assertEquals(self::$test, 'testtesttest');
    }

    public function testSendResponse()
    {
        // Make instance of app.
        $app = new \Core\Core\Core(__DIR__ . '/../MockApp');

        $app->getContainer()['response']->setBody('<div>Test</div>');

        $this->expectOutputString('<div>Test</div>');

        $app->sendResponse();
    }
    
    public function testHooks()
    {
        // Make instance of app.
        $app = new \Core\Core\Core(__DIR__ . '/../MockApp');

        // Make hooks.
        $app->setHook('before.execute', new TestHook());
        $app->setHook('after.execute', new TestHook());
        $app->setHook('before.routing', new TestHook());
        $app->setHook('after.routing', new TestHook());
        $app->setHook('after.response', new TestHook());

        // Test hooks.
        $this->assertTrue(is_callable($app->getHook('before.execute') ));
        $this->assertTrue(is_callable($app->getHook('after.execute') ));
        $this->assertTrue(is_callable($app->getHook('before.routing') ));
        $this->assertTrue(is_callable($app->getHook('after.routing') ));
        $this->assertTrue(is_callable($app->getHook('after.response') ));

        $app->execute()->sendResponse();

        $this->assertEquals(5, self::$hookCounter);

        $this->assertEquals(self::$test, 'testtesttesttesttesttest');
    }

    public function testMiddleware()
    {
        $app = new \Core\Core\Core(__DIR__ . '/../MockApp');

        $app->addMiddleware(new TestMiddleware3());
        $app->addMiddleware(new TestMiddleware2());
        $app->addMiddleware(new TestMiddleware1());

        $app->execute();

        $this->assertEquals('123', self::$middlewareStack);

        $this->assertEquals(self::$test, 'testtesttesttesttesttesttesttesttest');
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

        $app->setHook('not.found', function() {
                (new TestHook())->notFound();
            }
        );

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

        $app->setHook('internal.error', function() {
                (new TestHook())->error();
            }
        );

        $app->getContainer()['request']->setUri('error');

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

    public function __invoke($request, $response, $next)
    {
        $this->index();

        if ($next) {
            $next($request, $response);
        }
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
    public function __invoke($request, $response, $next)
    {
        CoreTest::$middlewareStack .= '1';

        if ($next) {
            $next($request, $response);
        }
    }
}

class TestMiddleware2
{
    public function __invoke($request, $response, $next)
    {
        CoreTest::$middlewareStack .= '2';

        if ($next) {
            $next($request, $response);
        }
    }
}

class TestMiddleware3
{
    public function __invoke($request, $response, $next)
    {
        CoreTest::$middlewareStack .= '3';

        if ($next) {
            $next($request, $response);
        }
    }
}