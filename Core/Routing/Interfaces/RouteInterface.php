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
     * Execute route
     *
     * @param ResolverInterface $resolver
     */
    public function __invoke(ResolverInterface $resolver);
}