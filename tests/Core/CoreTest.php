<?php

use Core\Container\Container;
use Core\Middleware\CorsMiddleware;

class CoreTest extends \PHPUnit\Framework\TestCase
{
    public static $test = '';

    public static $hookCounter = 0;

    public static $middlewareStack = '';

    public function testCustomConstruct()
    {
        $container = new \Core\Container\Container(__DIR__ . '/../MockApp');
        $resolver = new \Core\Core\Resolver($container);
        $app = new \Core\Core\Core($container);

        $this->assertEquals($container, $app->getContainer());
    }

    public function testConstruct()
    {
        $app = new \Core\Core\Core(new Container(__DIR__ . '/../MockApp'));

        \Core\Routing\Router::$CONTROLLERS_ROOT = '';

        // Check if construct made all required things.
        $this->assertInstanceOf('Core\Core\Core', $app);
    }

    
    public function testHooks()
    {
        // Make instance of app.
        $app = new \Core\Core\Core(new Container(__DIR__ . '/../MockApp'));

        // Make hooks.
        $app->setHook('after.execute', new TestHook());

        // Test hooks.
        $this->assertTrue(is_callable($app->getHook('after.execute')));

        $app->execute(true);

        $this->assertEquals(1, self::$hookCounter);
    }

    public function testMiddleware()
    {
        $cont = new Container(__DIR__ . '/../MockApp');
        $app = new \Core\Core\Core($cont);

        $app->addMiddleware(new CorsMiddleware($cont));
        $app->addMiddleware(new \Core\Middleware\JSONParserMiddleware($cont));

        $response = $app->execute(true);

        $this->assertEquals($response, null);
    }

    public function testNotFound()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/notfound';

        $app = new \Core\Core\Core(new Container(__DIR__ . '/../MockApp'));

        $response = $app->execute(true);

        $this->assertEquals($response->getStatusCode(), 404);
    }

    public function testControllerNotFound()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/notfound';

        $app = new \Core\Core\Core(new Container(__DIR__ . '/../MockApp'));

        $app->getContainer()['router']->addRoute(new \Core\Routing\Route('notfound', 'GET', 'TestController', 'notFound'));


        $app->setHook('not.found', function($e) {
                return (new \Core\Http\Response())->writeBody('Not found');
            }
        );

        $r = $app->execute(true);

        $this->assertEquals('Not found', (string)$r->getBody());
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

        $this->assertIsCallable($app->getHook('not.found'));
    }

    public function testControllerException()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/error';

        $app = new \Core\Core\Core(new Container(__DIR__ . '/../MockApp'));

        $app->getContainer()['router']->addRoute(new \Core\Routing\Route('error', 'GET','TestController', 'error'));

        $r = $app->execute(true);

        $this->assertEquals('Internal error: ' . 'Error', (string)$r->getBody());
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

        $this->assertIsCallable($app->getHook('internal.error'));
    }
}

class TestController
{
    public function index()
    {
        CoreTest::$test .= 'test';

        return new \Core\Http\Response();
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

        return $next();
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

        return $next();
    }
}

class TestMiddleware2
{
    public function __invoke($next)
    {
        CoreTest::$middlewareStack .= '2';

        return $next();
    }
}

class TestMiddleware3
{
    public function __invoke($next)
    {
        CoreTest::$middlewareStack .= '3';

        return $next();
    }
}