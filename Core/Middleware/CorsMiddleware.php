<?php

namespace Core\Middleware;

use Core\Container\Container;
use Core\Container\ContainerAware;
use Core\Http\Request;
use Core\Http\Response;
use Psr\Container\ContainerInterface;

/**
 * Class CorsMiddleware
 * @property Request request
 */
class CorsMiddleware extends ContainerAware
{
    /**
     * LoggingMiddleware constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param callable $next
     * @return Response
     */
    public function __invoke(callable $next)
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

        error_log(print_r($response, 1));
        return $response;
    }
}