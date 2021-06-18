<?php
namespace Core\Routing\Interfaces;

/**
 * RouterInterface
 *
 * @author <milos@activ8.rs>
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
    public function execute(string $uri, string $requestMethod): ?RouteInterface;

    /**
     * Add a route object to the router accepting GET request method.
     *
     * @param string $url
     * @param string $class
     * @param string $function
     * @return RouteInterface
     */
    public function get(string $url, string $class, string $function): RouteInterface;

    /**
     * Add a route object to the router accepting POST request method.
     *
     * @param string $url
     * @param string $class
     * @param string $function
     * @return RouteInterface
     */
    public function post(string $url, string $class, string $function): RouteInterface;

    /**
     * Add a route object to the router accepting PUT request method.
     *
     * @param string $url
     * @param string $class
     * @param string $function
     * @return RouteInterface
     */
    public function put(string $url, string $class, string $function);

    /**
     * Add a route object to the router accepting DELETE request method.
     *
     * @param string $url
     * @param string $class
     * @param string $function
     * @return RouteInterface
     */
    public function delete(string $url, string $class, string $function): RouteInterface;

    /**
     * Add custom route object to routes array.
     *
     * @param RouteInterface
     * @return self
     */
    public function addRoute(RouteInterface $route): RouterInterface;

    /**
     * Get list of routes.
     *
     * @return RouteInterface[]
     */
    public function getRoutes(): array;

    /**
     * Clear all routes.
     *
     * @return self
     */
    public function clearRoutes(): RouterInterface;
}