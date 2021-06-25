<?php
use Core\Routing\Route;
use Core\Routing\Router;

class RouterTest extends \PHPUnit\Framework\TestCase
{
	public function testRun()
	{
		// Create router object
		$router = new Router();

		$route = new Route('foo/bar', 'GET', 'foo', 'bar');
		// Create mock route and add it to router
		$router->addRoute($route);

		// Inject request and run test
		$this->assertEquals($router->execute('foo/bar', 'GET'), $route);

		$this->assertEquals($router->execute('foo/bar', 'POST'), false);
	}

	public function testRouteAdd()
	{
		// Create router object
		$router = new Router();
		
		$route1 = $router->get('foo/bar', '', '');

		$route2 = $router->post('bar/foo', '', '');

		$route3 = $router->put('bar/foo2', '', '');

		$route4 = $router->delete('bar/foo3', '', '');

		$route5 = $router->get('bar/foo4', '', '');

        $route6 = $router->patch('bar/foo6', '', '');

        $route7 = $router->options('bar/foo7', '', '');

		$this->assertContains($route1, $router->getRoutes());

		$this->assertContains($route2, $router->getRoutes());

		$this->assertContains($route3, $router->getRoutes());

		$this->assertContains($route4, $router->getRoutes());

		$this->assertContains($route5, $router->getRoutes());

        $this->assertContains($route6, $router->getRoutes());

        $this->assertContains($route7, $router->getRoutes());

        $router->clearRoutes();

        $this->assertEquals([], $router->getRoutes());


	}

	public function testGroups()
	{
		// Create router object
		$router = new Router();

		$router->group('api', function($router) {
			$router->get('foo/bar', '', '');

			$router->get('bar/foo', '', '');
		});

		$router->get('test', '', '');

		$router->group('api', function($router) {
			$router->group('v2', function($router) {
				$router->get('foo/bar', '', '');
			});
		});

        $router->get('test/test', '', '');

        $router->group('', function($router) {
            $router->get('no_prefix', '', '');
        });

		$routes = $router->getRoutes();

		$this->assertTrue($routes[0]->matches('api/foo/bar','GET'));

		$this->assertTrue($routes[1]->matches('api/bar/foo','GET'));

		$this->assertTrue($routes[2]->matches('test','GET'));

		$this->assertTrue($routes[3]->matches('api/v2/foo/bar','GET'));

		$this->assertTrue($routes[4]->matches('test/test','GET'));

        $this->assertTrue($routes[5]->matches('no_prefix','GET'));
	}
}