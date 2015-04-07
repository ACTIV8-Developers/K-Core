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
     * @return null|\Core\Routing\Route
     */
    public function run($uri, $requestMethod);
}