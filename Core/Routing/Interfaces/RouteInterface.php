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
     * Check if requested URI matches this route.
     *
     * @param string $uri
     * @param string method
     * @return bool
     */
    public function matches($uri, $method);
}