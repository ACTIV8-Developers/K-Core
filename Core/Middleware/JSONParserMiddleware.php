<?php

namespace Core\Middleware;

use Core\Container\Container;
use Core\Container\ContainerAware;
use Core\Http\Request;
use Core\Http\Response;

/**
 * Class JSONParserMiddleware
 * @property Request request
 */
class JSONParserMiddleware extends ContainerAware
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
        if (strpos($this->request->getContentType(), "json") !== false) {
            $this->container['data'] = json_decode($this->request->getBody(), true);
        }

        return $next();
    }
}