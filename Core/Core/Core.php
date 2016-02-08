<?php
namespace Core\Core;

use Core\Http\Interfaces\ResponseInterface;
use Exception;
use Core\Routing\Executable;
use Core\Container\ContainerAware;
use Core\Core\Exceptions\StopException;
use Interop\Container\ContainerInterface;
use Core\Core\Exceptions\NotFoundException;
use Core\Routing\Interfaces\RouteInterface;

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
    const VERSION = '3.0.0';

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
        'before.execute' => null,
        'before.routing' => null,
        'after.response' => null,
        'not.found' => null,
        'internal.error' => null
    ];

    /**
     * Core constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
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
     * @param ContainerInterface|null $container
     * @return Core
     */
    public static function getInstance(ContainerInterface $container = null)
    {
        if (null === self::$instance) {
            self::$instance = new Core($container);
        }
        return self::$instance;
    }

    /**
     * Route request and execute associated action.
     *
     * @return self
     */
    public function execute()
    {
        // Before execute hook.
        if (isset($this->hooks['before.execute'])) {
            $this->hooks['before.execute']();
        }

        try {
            // Execute middleware stack
            /** @var callable $start */
            $start = $this->middleware->top();
            $response = $start();
        } catch (StopException $e) {
            // Just stop execution of current stack
        } catch (NotFoundException $e) {
            $response = $this->notFound($e);
        } catch (Exception $e) {
            $response = $this->internalError($e);
        }

        if (isset($response)) {
            $this->container['response'] = $response;
        }
        return $this;
    }

    /**
     * Find targeted controller and add its actions to middleware stack
     * @throws NotFoundException
     */
    public function __invoke()
    {
        // Get router object
        $route = $this->container->get('router');

        // Collect routes list from file.
        if (is_file($this->container['config']['routesPath'])) {
            include $this->container['config']['routesPath'];
        }

        // Before routing hook
        if (isset($this->hooks['before.routing'])) {
            $this->hooks['before.routing']();
        }

        // Route requests
        /** @var RouteInterface $matchedRoute */
        $matchedRoute = $route->execute($this->container->get('request')->getUri(),
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
     * Send application response.
     *
     * @return self
     */
    public function sendResponse()
    {
        // Send final response.
        $this->container->get('response')->send();

        // Post response hook.
        if (isset($this->hooks['after.response'])) {
            $this->hooks['after.response']();
        }

        return $this;
    }

    /**
     * Handle 404.
     *
     * @param NotFoundException $e
     * @return ResponseInterface
     */
    protected function notFound(NotFoundException $e = null)
    {
        if ($e === null) {
            $e = new NotFoundException();
        }

        if (isset($this->hooks['not.found'])) {
            return $this->hooks['not.found']($e);
        } else {
            $this->container->get('response')->setStatusCode(404);
            $this->container->get('response')->setBody($e->getMessage());
            return $this->container->get('response');
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
            $this->container->get('response')->setStatusCode(500);
            $this->container->get('response')->setBody('Internal error: ' . $e->getMessage());
            return $this->container->get('response');
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
