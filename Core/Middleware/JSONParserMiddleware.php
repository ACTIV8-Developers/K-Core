<?php

namespace Core\Middleware;

use Core\Container\Container;
use Core\Container\ContainerAware;
use Core\Http\Request;
use Core\Http\Response;
use Psr\Container\ContainerInterface;

/**
 * Class JSONParserMiddleware
 * @property Request request
 */
class JSONParserMiddleware extends ContainerAware
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
        if (strpos($this->request->getContentType(), "json") !== false) {
            $this->container['data'] = json_decode($this->request->getBody(), true);
        }

        return $next();
    }
}