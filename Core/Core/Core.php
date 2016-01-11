<?php
namespace Core\Core;

use Exception;
use Core\Http\Request;
use Core\Http\Response;
use Core\Routing\Router;
use Core\Routing\Executable;
use Core\Container\Container;
use Core\Container\ContainerAware;
use Core\Core\Exceptions\StopException;
use Interop\Container\ContainerInterface;
use Core\Core\Exceptions\NotFoundException;
use Core\Routing\Interfaces\RouteInterface;
use Core\Routing\Interfaces\ResolverInterface;

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
    const VERSION = '2.5.0 RC1';

    /**
     * @var Core
     */
    protected static $instance;

    /**
     * @var string
     */
    protected $appPath = '';

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
     * @var ResolverInterface
     */
    protected $resolver = null;

    /**
     * Array of hooks to be applied.
     *
     * @var callable[]
     */
    protected $hooks = [
        'before.execute' => null,
        'before.routing' => null,
        'after.routing' => null,
        'after.response' => null,
        'not.found' => null,
        'internal.error' => null
    ];

    /**
     * Core constructor.
     *
     * @param string $appPath
     * @param ContainerInterface $container
     * @param ResolverInterface $resolver
     */
    public function __construct($appPath = '', ContainerInterface $container = null, ResolverInterface $resolver = null)
    {
        // Set app path
        $this->appPath = $appPath;

        // Set container
        $this->container = $container === null ? new Container() : $container;

        // Set class resolver
        $this->resolver = $resolver === null ? new Resolver($this->container) : $resolver;

        // Load application configuration.
        if (is_file($appPath . '/Config/Config.php')) {
            $config = include $appPath . '/Config/Config.php';
        } else {
            $config = [];
        }

        // Set app routes path
        $config['routesPath'] = $appPath . '/routes.php';

        // Set path where views are stored
        $config['viewsPath'] = $appPath . '/Views';

        // Store config
        $this->container['config'] = $config;

        // Create request class closure.
        $this->container['request'] = function () {
            return new Request($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
        };

        // Create response class closure.
        $this->container['response'] = function ($c) {
            $response = new Response();
            $response->setProtocolVersion($c['request']->getProtocolVersion());
            return $response;
        };

        // Create router class closure
        $this->container['router'] = function () {
            return new Router();
        };

        // Create middleware stack
        $this->middleware = new \SplStack();
        $this->middleware->setIteratorMode(\SplDoublyLinkedList::IT_MODE_LIFO | \SplDoublyLinkedList::IT_MODE_KEEP);

        // Add routing on top of the stack
        $this->middleware[] = $this;
    }

    /**
     * Get singleton instance of Core class.
     *
     * @param string $appPath
     * @param ContainerInterface|null $container
     * @param ResolverInterface|null $resolver
     * @return Core
     */
    public static function getInstance($appPath = '', ContainerInterface $container = null, ResolverInterface $resolver = null)
    {
        if (null === self::$instance) {
            self::$instance = new Core($appPath, $container, $resolver);
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
            $start();
        } catch (StopException $e) {
            // Just stop execution of current route
        } catch (NotFoundException $e) {
            $this->notFound($e);
        } catch (Exception $e) {
            $this->internalError($e);
        }

        // After execute hook.
        if (isset($this->hooks['after.execute'])) {
            $this->hooks['after.execute']();
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
        $matchedRoute = $route->execute($this->container->get('request')->getUri(), $this->container->get('request')->getMethod());

        // Execute route if found.
        if (null !== $matchedRoute) {
            // Get passed route params and append it to request object
            $this->container->get('request')->get->add($matchedRoute->getParams());

            // Execute matched route
            $matchedRoute($this->resolver);
        } else {
            throw new NotFoundException();
        }

        // Post routing hook
        if (isset($this->hooks['after.routing'])) {
            $this->hooks['after.routing']();
        }
    }

    /**
     * Send application response.
     *
     * @return self
     */
    public function sendResponse()
    {
        // Send final response.
        $this->container['response']->send();

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
     */
    protected function notFound(NotFoundException $e = null)
    {
        if ($e === null) {
            $e = new NotFoundException();
        }

        if (isset($this->hooks['not.found'])) {
            $this->container['not.found'] = $e;
            $this->hooks['not.found']();
        } else {
            $this->container['response']->setStatusCode(404);
            $this->container['response']->setBody($e->getMessage());
        }
    }

    /**
     * Handle exception.
     *
     * @param Exception $e
     */
    protected function internalError(Exception $e)
    {
        if (isset($this->hooks['internal.error'])) {
            $this->container['exception'] = $e;
            $this->hooks['internal.error']();
        } else {
            $this->container['response']->setStatusCode(500);
            $this->container['response']->setBody('Internal error: ' . $e->getMessage());
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

    /**
     * @param string $appPath
     * @return self
     */
    public function setAppPath($appPath)
    {
        $this->appPath = $appPath;
        return $this;
    }

    /**
     * @return string
     */
    public function getAppPath()
    {
        return $this->appPath;
    }
}
