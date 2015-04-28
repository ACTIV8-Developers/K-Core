<?php
namespace Core\Routing;

use Core\Routing\Interfaces\RouterInterface;

/**
 * Router class.
 *
 * This class contains list of application routes,
 * also routes serves as route factory and
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
     * otherwise return null.
     *
     * @var string $uri
     * @var string $requestMethod
     * @return null|\Core\Routing\Route
     */
    public function run($uri, $requestMethod)
    {
        // Find correct route.
        foreach ($this->routes as $route) {
            if (true === $route->matches($uri, $requestMethod)) {
                return $route;
            }
        }
        return null;
    }

    /**
     * Add a route object to the router accepting GET request method.
     *
     * @param string $url
     * @param string $class
     * @param string $function
     * @return \Core\Routing\Route
     */
    public function get($url, $class, $function)
    {
        $route = new Route($url, 'GET', $class, $function);
        $this->routes[] = $route;
        return $route;
    }

    /**
     * Add a route object to the router accepting POST request method.
     *
     * @param string $url
     * @param string $class
     * @param string $function
     * @return \Core\Routing\Route
     */
    public function post($url, $class, $function)
    {
        $route = new Route($url, 'POST', $class, $function);
        $this->routes[] = $route;
        return $route;
    }

    /**
     * Add a route object to the router accepting PUT request method.
     *
     * @param string $url
     * @param string $class
     * @param string $function
     * @return \Core\Routing\Route
     */
    public function put($url, $class, $function)
    {
        $route = new Route($url, 'PUT', $class, $function);
        $this->routes[] = $route;
        return $route;
    }

    /**
     * Add a route object to the router accepting DELETE request method.
     *
     * @param string $url
     * @param string $class
     * @param string $function
     * @return \Core\Routing\Route
     */
    public function delete($url, $class, $function)
    {
        $route = new Route($url, 'DELETE', $class, $function);
        $this->routes[] = $route;
        return $route;
    }

    /**
     * Add custom route object to routes array.
     *
     * @param Route
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
     * @return \Core\Routing\Route
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