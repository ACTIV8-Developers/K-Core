<?php 
namespace Core\Routing;

/**
* Router class.
*
* This class contains list of application routes,
* also here are methods for adding things to list and
* here is defined run method for routing requests.
*
* @author <milos@caenazzo.com>
*/
class Router
{
	/**
	* Collection of routes.
    *
	* @var array (\Core\Routing\Route)
	**/
	protected $routes = [];

	/**
	* Check routes and return matching one.
    *
	* @var string $uri
    * @var string $requestMethod
	* @return bool|object \Core\Routing\Route
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
    * @return object \Core\Routing\Route
    */
    public function get($url, array $callable)
    {
    	$route = new Route($url, $callable, 'GET');
		$this->routes[] = $route;
        return $route;
    }

    /**
	* Add a route object to the router accepting POST request method.
    *
	* @param string $url
	* @param array $callable,
    * @return object \Core\Routing\Route
    */
    public function post($url, array $callable)
    {
    	$route = new Route($url, $callable, 'POST');
		$this->routes[] = $route;
        return $route;
    }

    /**
	* Add a route object to the router accepting PUT request method.
    *
	* @param string $url
	* @param array $callable,
    * @return object \Core\Core\Route
	*/
    public function put($url, array $callable)
    {
    	$route = new Route($url, $callable, 'PUT');
		$this->routes[] = $route;
        return $route;
    }

    /**
	* Add a route object to the router accepting DELETE request method.
    *
	* @param string $url
	* @param array $callable,
    * @return object \Core\Core\Route
	*/
    public function delete($url, array $callable)
    {
    	$route = new Route($url, $callable, 'DELETE');
		$this->routes[] = $route;
        return $route;
    }

    /**
    * Add custom route object to routes array.
    *
    * @var object \Core\Core\Route
    */
    public function addRoute(Route $route)
    {
    	$this->routes[] = $route;
    }

    /**
     * Get list of routes.
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
    * Clear all routes.
    */
    public function clearRoutes()
    {
        unset($this->routes);
        $this->routes = [];
    }
}