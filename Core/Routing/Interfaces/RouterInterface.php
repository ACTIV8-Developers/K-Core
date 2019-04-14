<?php
namespace Core\Routing\Interfaces;

/**
 * RouterInterface
 *
 * @author <milos@caenazzo.com>
 */
interface RouterInterface
{
    /**
     * Check routes and returns matching one if found,
     * otherwise return null.
     *
     * @var string $uri
     * @var string $requestMethod
     * @return null|RouteInterface
     */
    public function execute($uri, $requestMethod);

    /**
     * Add a route object to the router accepting GET request method.
     *
     * @param string $url
     * @param string $class
     * @param string $function
     * @return RouteInterface
     */
    public function get($url, $class, $function);

    /**
     * Add a route object to the router accepting POST request method.
     *
     * @param string $url
     * @param string $class
     * @param string $function
     * @return RouteInterface
     */
    public function post($url, $class, $function);

    /**
     * Add a route object to the router accepting PUT request method.
     *
     * @param string $url
     * @param string $class
     * @param string $function
     * @return RouteInterface
     */
    public function put($url, $class, $function);

    /**
     * Add a route object to the router accepting DELETE request method.
     *
     * @param string $url
     * @param string $class
     * @param string $function
     * @return RouteInterface
     */
    public function delete($url, $class, $function);

    /**
     * Add custom route object to routes array.
     *
     * @param RouteInterface
     * @return self
     */
    public function addRoute(RouteInterface $route);

    /**
     * Get list of routes.
     *
     * @return RouteInterface[]
     */
    public function getRoutes();

    /**
     * Clear all routes.
     *
     * @return self
     */
    public function clearRoutes();
}