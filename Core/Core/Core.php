<?php
namespace Core\Core;

use Exception;
use Core\Routing\Router;
use Core\Routing\Executable;
use BadFunctionCallException;
use Core\Container\Container;
use Core\Container\ContainerAware;
use Core\Core\Exceptions\StopException;
use Interop\Container\ContainerInterface;
use Core\Core\Exceptions\NotFoundException;
use Core\Routing\Interfaces\RouteInterface;
use Core\Routing\Interfaces\ResolverInterface;
use Core\Http\ServerRequestFactory;
use Zend\Diactoros\Response;

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
    const VERSION = '3.0.0 RC1';

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
        if ($container === null) {
            $this->container = new Container();
        } else {
            $this->container = $container;
        }

        // Set class resolver
        if ($resolver === null) {
            $this->resolver = new Resolver($this->container);
        } else {
            $this->resolver = $resolver;
        }

        // Load application configuration.
        if (is_file($appPath . '/Config/Config.php')) {
            $config = require $appPath . '/Config/Config.php';
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
            return ServerRequestFactory::fromGlobals(
                $_SERVER,
                $_GET,
                $_POST,
                $_COOKIE,
                $_FILES
            );
        };

        // Create response class closure.
        $this->container['response'] = function () {
            return new Response();
        };

        // Create router class closure
        $this->container['router'] = function () {
            return new Router();
        };

        // Create middleware stack
        $this->middleware = new \SplStack();
        $this->middleware->setIteratorMode(\SplDoublyLinkedList::IT_MODE_LIFO | \SplDoublyLinkedList::IT_MODE_KEEP);
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
     * @throws BadFunctionCallException
     * @return self
     */
    public function execute()
    {
        // Pre execute hook.
        if (isset($this->hooks['before.execute'])) {
            $this->hooks['before.execute']();
        }

        // Find targeted controller and append its actions to middleware stack
        $this->executeRouting();

        try {
            // Execute middleware stack
            $this->executeMiddlewareStack();
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
     */
    public function executeRouting()
    {
        // Get router object
        $route = $this->container->get('router');

        // Collect routes list from file.
        if (is_file($this->container['config']['routesPath'])) {
            include $this->container['config']['routesPath'];
        }

        // Pre routing hook
        if (isset($this->hooks['before.routing'])) {
            $this->hooks['before.routing']();
        }

        // Route requests
        /** @var RouteInterface $matchedRoute */
        $matchedRoute = $route->execute(trim($this->container['request']->getUri()->getPath(), '/'), $this->container['request']->getMethod());

        // Execute route if found.
        if (null !== $matchedRoute) {

            // Add route post executable
            if (($after = $matchedRoute->getAfterExecutable()) !== null) {
                $this->addMiddleware($after);
            }

            // Add found route main executable to middleware stack
            $this->addMiddleware(function($request, $response, $next) use ($matchedRoute) {
                /** @var Executable $executable */
                $executable = $matchedRoute->getExecutable();

                // Get passed route params
                $params = $matchedRoute->getParams();

                // Append passed params to GET array.
                //$request->get->add($params);

                // Pass params to executable also
                $executable->setParams($params);

                // Add executable resolver
                $executable->setResolver($this->resolver);

                $executable();

                if ($next) {
                    $next($request, $response);
                }
            });

            // Add route pre executable
            if (($before = $matchedRoute->getBeforeExecutable()) !== null) {
                $this->addMiddleware($before);
            }
        } else {
            $this->addMiddleware(function() {
                // If page not found display 404 error.
                throw new NotFoundException();
            });
        }

        // Post routing hook
        if (isset($this->hooks['after.routing'])) {
            $this->hooks['after.routing']();
        }
    }

    /**
     * @return mixed
     */
    protected function executeMiddlewareStack()
    {
        /** @var callable $start */
        $start = $this->middleware->top();
        $start($this->container['request'], $this->container['response']);
    }

    /**
     * Send application response.
     *
     * @throws BadFunctionCallException
     * @return self
     */
    public function sendResponse()
    {
        // Send response
        $response = $this->container->get('response');

        // Headers
        if (!headers_sent()) {
            // Status
            header(sprintf(
                'HTTP/%s %s %s',
                $response->getProtocolVersion(),
                $response->getStatusCode(),
                $response->getReasonPhrase()
            ));
            // Headers
            foreach ($response->getHeaders() as $name => $values) {
                foreach ($values as $value) {
                    header(sprintf('%s: %s', $name, $value), false);
                }
            }
        }

        // Body
        $body = $response->getBody();
        if ($body->isSeekable()) {
            $body->rewind();
        }
        $settings = $this->container->get('config');
        $chunkSize = isset($settings['responseChunkSize']) ? $settings['responseChunkSize'] : 1024;
        $contentLength = $response->getHeaderLine('Content-Length');

        if (!$contentLength) {
            $contentLength = $body->getSize();
        }

        $totalChunks = ceil($contentLength / $chunkSize);
        $lastChunkSize = $contentLength % $chunkSize;
        $currentChunk = 0;
        while (!$body->eof() && $currentChunk < $totalChunks) {
            if (++$currentChunk == $totalChunks && $lastChunkSize > 0) {
                $chunkSize = $lastChunkSize;
            }
            echo $body->read($chunkSize);
            if (connection_status() != CONNECTION_NORMAL) {
                break;
            }
        }

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
            //$this->container['response']->withStatus(404);
            $this->container['response']->getBody()->write($e->getMessage());
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
            //$this->container['response']->withStatus(404);
            $this->container['response']->getBody()->write('Internal error: ' . $e->getMessage());
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
        $next = null;
        if (!$this->middleware->isEmpty()) {
            $next = $this->middleware->top();
        }

        $this->middleware[] = function ($request, $response) use ($callable, $next) {
            return call_user_func($callable, $request, $response, $next);
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
