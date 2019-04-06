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
     * @param callable $callable
     * @return $this
     */
    public function addMiddleware(callable $callable);

    /**
     * Execute route
     *
     * @param ResolverInterface $resolver
     */
    public function __invoke(ResolverInterface $resolver);
}