<?php
namespace Core\Routing\Interfaces;

/**
 * RouteInterface
 *
 * @author <milos@caenazzo.com>
 */
interface RouteInterface
{
    /**
     * Check if requested URI and method matches this route.
     *
     * @param string $uri
     * @param string method
     * @return bool
     */
    public function matches($uri, $method);

    /**
     * Get parameters associated passed with route if matched
     *
     * @return array
     */
    public function getParams();

    /**
     * Get class associated with route
     *
     * @return string
     */
    public function getClass();

    /**
     * Get method/function associated with route
     *
     * @return string
     */
    public function getFunction();
}