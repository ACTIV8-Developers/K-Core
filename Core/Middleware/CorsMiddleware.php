<?php

namespace Core\Middleware;

use Core\Container\Container;
use Core\Container\ContainerAware;
use Core\Http\Request;
use Core\Http\Response;

/**
 * Class CorsMiddleware
 * @property Request request
 */
class CorsMiddleware extends ContainerAware
{
    /**
     * LoggingMiddleware constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param callable $next
     * @return Response
     */
    public function __invoke($next)
    {
        if ($this->request->isOptions()) {
            return (new Response())
                ->setHeader('Access-Control-Allow-Origin', "*")
                ->setHeader('Access-Control-Allow-Methods', "POST, GET, DELETE, PUT, PATCH, OPTIONS")
                ->setHeader('Access-Control-Allow-Headers', "Origin, Authorization, Content-Type")
                ->setHeader('Access-Control-Max-Age', "0")
                ->setHeader('Content-Length', "0")
                ->setHeader('Content-Type', "text/plain");
        }

        $response = $next();

        if ($response instanceof Response) {
            $response->setHeader('Access-Control-Allow-Origin', '*');
            $response->setHeader('Access-Control-Allow-Credentials', 'true');
        }

        return $response;
    }
}