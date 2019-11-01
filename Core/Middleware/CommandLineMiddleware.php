<?php

namespace Core\Middleware;

use Core\Services\JobQueue\JobExecutor;
use Core\Container\Container;
use Core\Container\ContainerAware;
use Core\Http\Response;

/**
 * Class CommandLineMiddleware
 * @property \Core\Http\Request request
 * @property JobExecutor executor
 */
class CommandLineMiddleware extends ContainerAware
{
    /**
     * @var array
     */
    private $argv;

    /**
     * AuthMiddleware constructor.
     *
     * @param Container $container
     * @param array $argv
     */
    public function __construct(Container $container, array $argv)
    {
        $this->argv = $argv;
        $this->container = $container;
    }

    /**
     * @param callable $next
     * @return callable|Response
     */
    public function __invoke($next)
    {
        $command = $this->argv[1];

        // Call next middleware
        return (new Response())->setBody($this->$command());
    }

    public function executor()
    {
        return $this->executor->execute();
    }

    public function status()
    {
        return $this->executor->status();
    }
}