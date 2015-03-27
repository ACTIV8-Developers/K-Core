<?php 
namespace Core\Routing;

use Core\Routing\Interfaces\RouterInterface;

/**
 * Router class.
 *
 * This class contains list of application routes,
 * also here are methods for adding things to list and
 * here is defined run method for routing requests.
 *
 * @author <milos@caenazzo.com>
 */
class Router implements RouterInterface
{
	/**
	 * Collection of routes.
     *
	 * @var \Core\Routing\Route[]
	 */
	protected $routes = [];

	/**
	 * Check routes and returns matching one if found,
     * otherwise return false.
     *
	 * @var string $uri
     * @var string $requestMethod
	 * @return bool|\Core\Routing\Route
	 */
	public function run($uri, $requestMethod)
	{
		// Find correct route.
	    foreach ($this->routes as $route) {
	    	if (true === $route->matches($uri, $requestMethod)) {
	        	return $route;
	      	}
	    }
	    return false;
	}

    /**
	 * Add a route object to the router accepting GET request method.
     *
	 * @param string $url
	 * @param array
     * @return \Core\Routing\Route
     */
    public function get($url, $callable)
    {
    	$route = new Route($url, $callable, 'GET');
		$this->routes[] = $route;
        return $route;
    }

    /**
	 * Add a route object to the router accepting POST request method.
     *
	 * @param string $url
	 * @param $callable
     * @return \Core\Routing\Route
     */
    public function post($url, $callable)
    {
    	$route = new Route($url, $callable, 'POST');
		$this->routes[] = $route;
        return $route;
    }

    /**
	 * Add a route object to the router accepting PUT request method.
     *
	 * @param string $url
	 * @param $callable
     * @return \Core\Core\Route
	 */
    public function put($url, $callable)
    {
    	$route = new Route($url, $callable, 'PUT');
		$this->routes[] = $route;
        return $route;
    }

    /**
	 * Add a route object to the router accepting DELETE request method.
     *
	 * @param string $url
	 * @param $callable
     * @return \Core\Core\Route
	 */
    public function delete($url, $callable)
    {
    	$route = new Route($url, $callable, 'DELETE');
		$this->routes[] = $route;
        return $route;
    }

    /**
     * Add custom route object to routes array.
     *
     * @param \Core\Core\Route
     * @return self
     */
    public function addRoute(Route $route)
    {
    	$this->routes[] = $route;
        return $this;
    }

    /**
     * Get list of routes.
     *
     * @return \Core\Core\Route[]
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Clear all routes.
     *
     * @return self
     */
    public function clearRoutes()
    {
        unset($this->routes);
        $this->routes = [];
        return $this;
    }
}