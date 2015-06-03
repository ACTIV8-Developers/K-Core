<?php
namespace Core\Routing\Interfaces;

use Core\Routing\Route;

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
     * @return null|\Core\Routing\Route
     */
    public function execute($uri, $requestMethod);

    /**
     * Add a route object to the router accepting GET request method.
     *
     * @param string $url
     * @param string $class
     * @param string $function
     * @return \Core\Routing\Route
     */
    public function get($url, $class, $function);

    /**
     * Add a route object to the router accepting POST request method.
     *
     * @param string $url
     * @param string $class
     * @param string $function
     * @return \Core\Routing\Route
     */
    public function post($url, $class, $function);

    /**
     * Add a route object to the router accepting PUT request method.
     *
     * @param string $url
     * @param string $class
     * @param string $function
     * @return \Core\Routing\Route
     */
    public function put($url, $class, $function);

    /**
     * Add a route object to the router accepting DELETE request method.
     *
     * @param string $url
     * @param string $class
     * @param string $function
     * @return \Core\Routing\Route
     */
    public function delete($url, $class, $function);

    /**
     * Add custom route object to routes array.
     *
     * @param \Core\Routing\Route
     * @return self
     */
    public function addRoute(Route $route);
}