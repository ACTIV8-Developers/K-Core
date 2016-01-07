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
     * @param string $method
     * @return bool
     */
    public function matches($uri, $method);

    /**
     * @return array
     */
    public function getParams();

    /**
     * Get executable associated with route
     *
     * @return callable
     */
    public function getExecutable();

    /**
     * Get after executable associated with route
     *
     * @return callable
     */
    public function getAfterExecutable();

    /**
     * Get pre executable associated with route
     *
     * @return callable
     */
    public function getBeforeExecutable();
}