<?php
namespace Core\Routing;

use Core\Routing\Interfaces\RouteInterface;
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
     * @var string
     */
    public static $CONTROLLERS_ROOT = 'App\Controllers\\';

    /**
     * Collection of routes.
     *
     * @var RouteInterface[]
     */
    protected $routes = [];

    /**
     * @var string
     */
    protected $urlPrefix = '';

    /**
     * @var array
     */
    protected $middlewares = [];

    /**
     * Check routes and returns matching one if found,
     * otherwise return null.
     *
     * @var string $uri
     * @var string $requestMethod
     * @return null|RouteInterface
     */
    public function execute($uri, $requestMethod)
    {
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
     * @return RouteInterface
     */
    public function get($url, $class, $function)
    {
        $route = new Route($this->urlPrefix . $url, 'GET', self::$CONTROLLERS_ROOT . $class, $function);
        $this->addRoute($route);
        return $route;
    }

    /**
     * Add a route object to the router accepting POST request method.
     *
     * @param string $url
     * @param string $class
     * @param string $function
     * @return RouteInterface
     */
    public function post($url, $class, $function)
    {
        $route = new Route($this->urlPrefix . $url, 'POST', self::$CONTROLLERS_ROOT . $class, $function);
        $this->addRoute($route);
        return $route;
    }

    /**
     * Add a route object to the router accepting PUT request method.
     *
     * @param string $url
     * @param string $class
     * @param string $function
     * @return RouteInterface
     */
    public function put($url, $class, $function)
    {
        $route = new Route($this->urlPrefix . $url, 'PUT', self::$CONTROLLERS_ROOT . $class, $function);
        $this->addRoute($route);
        return $route;
    }

    /**
     * Add a route object to the router accepting DELETE request method.
     *
     * @param string $url
     * @param string $class
     * @param string $function
     * @return RouteInterface
     */
    public function delete($url, $class, $function)
    {
        $route = new Route($this->urlPrefix . $url, 'PATCH', self::$CONTROLLERS_ROOT . $class, $function);
        $this->addRoute($route);
        return $route;
    }

    /**
     * Add a route object to the router accepting DELETE request method.
     *
     * @param string $url
     * @param string $class
     * @param string $function
     * @return RouteInterface
     */
    public function patch($url, $class, $function)
    {
        $route = new Route($this->urlPrefix . $url, 'PATCH', self::$CONTROLLERS_ROOT . $class, $function);
        $this->addRoute($route);
        return $route;
    }

    /**
     * Add a route object to the router accepting OPTIONS request method.
     *
     * @param string $url
     * @param string $class
     * @param string $function
     * @return RouteInterface
     */
    public function options($url, $class, $function)
    {
        $route = new Route($this->urlPrefix . $url, 'OPTIONS', self::$CONTROLLERS_ROOT . $class, $function);
        $this->addRoute($route);
        return $route;
    }

    /**
     * @param $prefix
     * @param callable $closure
     * @param array $middleware
     * @return $this
     */
    public function group($prefix, callable $closure, $middleware = [])
    {
        if ($prefix) {
            $this->urlPrefix .= $prefix . '/';
        } else {
            $this->urlPrefix = '';
        }
        $this->middlewares = $middleware;
        $closure($this);
        $this->middlewares = [];
        $this->urlPrefix = '';
        return $this;
    }

    /**
     * Add custom route object to routes array.
     *
     * @param RouteInterface $route
     * @return self
     */
    public function addRoute(RouteInterface $route)
    {
        foreach ($this->middlewares as $m) {
            $route->addMiddleware($m);
        }
        $this->routes[] = $route;
        return $this;
    }

    /**
     * Get list of routes.
     *
     * @return RouteInterface[]
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