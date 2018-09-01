<?php
namespace Core\Core;

use Core\Container\Container;
use Core\Container\ContainerAware;
use Core\Core\Exceptions\NotFoundException;
use Core\Http\Interfaces\ResponseInterface;
use Core\Http\Response;
use Core\Routing\Executable;
use Core\Routing\Interfaces\RouteInterface;
use Exception;

/**
 * Core class.
 *
 * This is the heart of whole application.
 *
 * @author <milos@caenazzo.com>
 */
class Core extends ContainerAware
{
    /**
     * Core version.
     *
     * @var string
     */
    const VERSION = '3.2.0';

    /**
     * @var Core
     */
    protected static $instance;

    /**
     * Array of middleware actions
     *
     * @var \SplStack
     */
    protected $middleware = [];

    /**
     * @var ContainerInterface
     */
    protected $container = null;

    /**
     * Array of hooks to be applied.
     *
     * @var callable[]
     */
    protected $hooks = [
        'after.execute' => null,
        'not.found' => null,
        'internal.error' => null
    ];

    /**
     * Core constructor.
     *
     * @param Container|ContainerInterface $container
     */
    public function __construct(Container $container)
    {
        // Set core container
        $this->container = $container;

        // Create middleware stack
        $this->middleware = new \SplStack();
        $this->middleware->setIteratorMode(\SplDoublyLinkedList::IT_MODE_LIFO | \SplDoublyLinkedList::IT_MODE_KEEP);

        // Add core on top of the middleware stack
        $this->middleware[] = $this;
    }

    /**
     * Get singleton instance of Core class.
     *
     * @param Container|null $container
     * @return Core
     */
    public static function getInstance(Container $container = null)
    {
        if (null === self::$instance) {
            self::$instance = new Core($container);
        }
        return self::$instance;
    }

    /**
     * Route request and execute associated action.
     *
     * @param bool $silent
     * @return ResponseInterface
     * @throws Exception
     */
    public function execute($silent = false)
    {
        try {
            // Execute middleware stack
            /** @var callable $start */
            $start = $this->middleware->top();
            $response = $start();
        } catch (NotFoundException $e) {
            $response = $this->notFound($e);
        } catch (Exception $e) {
            $response = $this->internalError($e);
        }

        // Send response
        if (isset($response) && !$silent) {
            if (!$response instanceof ResponseInterface) {
                throw new Exception("Controllers, hooks and middleware must return instance of ResponseInterface");
            }
            $response->send();
        }

        // Post response hook.
        if (isset($this->hooks['after.execute'])) {
            $this->hooks['after.execute']();
        }

        return $response;
    }

    /**
     * Find targeted controller and add its actions to middleware stack
     * @throws NotFoundException
     * @return ResponseInterface
     */
    public function __invoke()
    {
        // Get router object
        $route = $this->container->get('router');

        // Collect routes list from file.
        if (isset($this->container['config']['routesPath']) && is_file($this->container['config']['routesPath'])) {
            /** @noinspection PhpIncludeInspection */
            include $this->container['config']['routesPath'];
        }

        // Route requests
        /** @var RouteInterface $matchedRoute */
        $matchedRoute = $route
            ->execute($this->container->get('request')->getUri(),
            $this->container->get('request')->getMethod());

        // Execute route if found
        if (null !== $matchedRoute) {
            // Get passed route params and append it to request object
            $this->container->get('request')->get->add($matchedRoute->getParams());

            // Execute matched route
            $resolver = $this->container->has('resolver') ? $this->container->get('resolver') : null;
            $response = $matchedRoute($resolver);
        } else {
            throw new NotFoundException();
        }

        return $response;
    }

    /**
     * Handle 404.
     *
     * @param NotFoundException $e
     * @return ResponseInterface
     */
    protected function notFound(NotFoundException $e)
    {
        if (isset($this->hooks['not.found'])) {
            return $this->hooks['not.found']($e);
        } else {
            return (new Response())
                ->setStatusCode(404)
                ->setBody($e->getMessage());
        }
    }

    /**
     * Handle exception.
     *
     * @param Exception $e
     * @return ResponseInterface
     */
    protected function internalError(Exception $e)
    {
        if (isset($this->hooks['internal.error'])) {
            return $this->hooks['internal.error']($e);
        } else {
            return (new Response())
                ->setStatusCode(500)
                ->setBody('Internal error: ' . $e->getMessage());
        }
    }

    /**
     * Add hook
     *
     * @param $key
     * @param callable $hook
     * @return $this
     */
    public function setHook($key, callable $hook)
    {
        $this->hooks[$key] = $hook;

        return $this;
    }

    /**
     * Get hook.
     *
     * @param string $key
     * @return Executable
     */
    public function getHook($key)
    {
        return $this->hooks[$key];
    }

    /**
     * @param callable $callable
     * @return $this
     */
    public function addMiddleware(callable $callable)
    {
        $next = $this->middleware->top();
        $this->middleware[] = function () use ($callable, $next) {
            return call_user_func($callable, $next);
        };
        return $this;
    }
}
