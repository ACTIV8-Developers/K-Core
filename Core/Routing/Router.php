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
 * @author <milos@activ8.rs>
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
    public function execute(string $uri, string $requestMethod): ?RouteInterface
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
    public function get(string $url, string $class, string $function): RouteInterface
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
    public function post(string $url, string $class, string $function): RouteInterface
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
    public function put(string $url, string $class, string $function): RouteInterface
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
    public function delete(string $url, string $class, string $function): RouteInterface
    {
        $route = new Route($this->urlPrefix . $url, 'DELETE', self::$CONTROLLERS_ROOT . $class, $function);
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
    public function patch(string $url, string $class, string $function): RouteInterface
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
    public function options(string $url, string $class, string $function): RouteInterface
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
    public function group($prefix, callable $closure, $middleware = []): RouteInterface
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
    public function addRoute(RouteInterface $route): RouterInterface
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
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Clear all routes.
     *
     * @return self
     */
    public function clearRoutes(): RouterInterface
    {
        unset($this->routes);
        $this->routes = [];
        return $this;
    }
}