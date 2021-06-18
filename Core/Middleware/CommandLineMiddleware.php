<?php

namespace Core\Middleware;

use Core\Services\JobQueue\JobExecutor;
use Core\Container\Container;
use Core\Container\ContainerAware;
use Core\Http\Response;
use Psr\Container\ContainerInterface;

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
     * @param CommandLineMiddleware $container
     * @param array $argv
     */
    public function __construct(ContainerInterface $container, array $argv)
    {
        $this->argv = $argv;
        $this->container = $container;
    }

    /**
     * @param callable $next
     * @return callable|Response
     */
    public function __invoke(callable $next)
    {
        $command = $this->argv[1];

        // Call next middleware
        return (new Response())->setBody($this->$command());
    }

    public function executor(): string
    {
        return $this->executor->execute();
    }

    public function status()
    {
        return $this->executor->status();
    }
}