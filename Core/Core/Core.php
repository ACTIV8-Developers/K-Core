<?php
namespace Core\Core;

use BadFunctionCallException;
use Core\Container\Container;
use Core\Core\Exceptions\NotFoundException;
use Core\Core\Exceptions\StopException;
use Core\Database\Connections\MySQLConnection;
use Core\Database\Database;
use Core\Http\Request;
use Core\Http\Response;
use Core\Routing\Action;
use Core\Routing\Executable;
use Core\Routing\Interfaces\ExecutableInterface;
use Core\Routing\Router;
use Core\Session\Handlers\DatabaseSessionHandler;
use Core\Session\Handlers\EncryptedFileSessionHandler;
use Core\Session\Session;
use Exception;
use InvalidArgumentException;

/**
 * Core class.
 *
 * This is the heart of whole application.
 *
 * @author <milos@caenazzo.com>
 */
class Core extends Container
{
    /**
     * Core version.
     *
     * @var string
     */
    const VERSION = '1.57rc';

    /**
     * @var Core
     */
    protected static $instance;

    /**
     * @var bool
     */
    protected $isBooted = false;

    /**
     * @var string
     */
    protected $appPath = '';

    /**
     * @var string
     */
    protected $routesPath = '';

    /**
     * @var string
     */
    protected $configPath = '';

    /**
     * @var string
     */
    protected $databaseConfigPath = '';

    /**
     * @var string
     */
    protected $controllerNamespace = 'Controllers';

    /**
     * @var string
     */
    protected $viewsPath = '';

    /**
     * Array of middleware actions
     *
     * @var array
     */
    protected $middleware = [];

    /**
     * Array of hooks to be applied.
     *
     * @var array
     */
    protected $hooks = [
        'before.boot' => null,
        'after.boot' => null,
        'before.run' => null,
        'after.run' => null,
        'after.response' => null,
        'not.found' => null,
        'internal.error' => null
    ];

    /**
     * Class constructor.
     *
     * @param string $appPath
     */
    public function __construct($appPath = '')
    {
        // Invoke container construct
        parent::__construct();

        // Set app path
        $this->appPath = $appPath;

        // Set app configuration path
        $this->configPath = $appPath . '/Config/Config.php';

        // Set app database configuration path
        $this->databaseConfigPath = $appPath . '/Config/Database.php';

        // Set app routes path
        $this->routesPath = $appPath . '/routes.php';

        // Set path where views are stored
        $this->viewsPath = $appPath . '/Views';
    }

    /**
     * Boot application
     *
     * @return self
     * @throws \InvalidArgumentException
     */
    public function boot()
    {
        if (!$this->isBooted) {

            // Pre boot hook.
            if (isset($this->hooks['before.boot'])) {
                $this->hooks['before.boot']->execute();
            }

            // Load application configuration.
            if (is_file($this->configPath)) {
                $this['config'] = require $this->configPath;
            } else {
                $this['config'] = [];
            }

            // Create request class closure.
            $this['request'] = function () {
                return new Request($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
            };

            // Create response class closure.
            $this['response'] = function ($c) {
                $response = new Response();
                $response->setProtocolVersion($c['request']->getProtocolVersion());
                return $response;
            };

            // Create router class closure
            $this['router'] = function () {
                return new Router();
            };

            // Load database configuration.
            if (is_file($this->databaseConfigPath)) {
                $this['config.database'] = require $this->databaseConfigPath;
            } else {
                $this['config.database'] = [];
            }

            // For each needed database create database class closure.
            foreach ($this['config.database'] as $dbName => $dbConfig) {
                $this['db.' . $dbName] = function () use ($dbConfig) {
                    $db = null;
                    switch ($dbConfig['driver']) { // Choose connection and create it.
                        case 'mysql':
                            $db = new MySQLConnection($dbConfig);
                            break;
                        default:
                            throw new InvalidArgumentException('Error! Unsupported database connection type.');
                    }
                    // Inject it into database class.
                    $database = new Database();
                    $database->setConnection($db->connect());
                    return $database;
                };
            }

            // Create session class closure.
            $this['session'] = function ($c) {
                // Select session handler.
                $handler = null;
                switch ($c['config']['sessionHandler']) {
                    case 'encrypted-file':
                        $handler = new EncryptedFileSessionHandler();
                        break;
                    case 'database':
                        $name = $c['config']['session']['connName'];
                        $conn = $this['db.' . $name]->getConnection();
                        $handler = new DatabaseSessionHandler($conn);
                        break;
                }
                $session = new Session($c['config']['session'], $handler);
                $session->setHashKey($c['config']['key']);
                return $session;
            };

            // Register service providers.
            if (isset($this['config']['services']) && is_array($this['config']['services'])) {
                foreach ($this['config']['services'] as $service) {
                    $s = new $service();
                    $s->register($this);
                }
            }

            // Register middleware stack
            if (isset($this['config']['middleware'])) {
                foreach ($this['config']['middleware'] as $class => $function) {
                    $this->addMiddleware($class, $function);
                }
            }

            // After boot hook
            if (isset($this->hooks['after.boot'])) {
                $this->hooks['after.boot']->execute();
            }

            $this->isBooted = true;
        }

        return $this;
    }

    /**
     * Application main executive function.
     *
     * @throws BadFunctionCallException
     * @return self
     */
    public function run()
    {
        if (!$this->isBooted) {
            throw new BadFunctionCallException('Error! Application is not booted.');
        }

        try {
            $this->routeRequest();
        } catch (StopException $e) {
            // Just pass
        } catch (NotFoundException $e) {
            $this->notFound($e);
        } catch (Exception $e) {
            $this->internalError($e);
        }

        return $this;
    }

    /**
     * Route request and execute proper controller if route found.
     */
    protected function routeRequest()
    {
        // Get router object
        $route = $this['router'];

        // Collect routes list from file.
        if (is_file($this->routesPath)) {
            include $this->routesPath;
        }

        // Pre routing/controller hook.
        if (isset($this->hooks['before.run'])) {
            $this->hooks['before.run']->execute();
        }

        // Call middleware stack
        foreach ($this->middleware as $m) {
            $m->execute();
        }

        // Route requests
        $matchedRoute = $route->run($this['request']->getUri(), $this['request']->getMethod());

        // Execute route if found.
        if (null !== $matchedRoute) {
            // Append passed params to GET array.
            $this['request']->get->add($matchedRoute->params);

            // Execute matched route.
            $this->execute($matchedRoute->class, $matchedRoute->function,
                        $matchedRoute->params, $this->controllerNamespace);
        } else {
            // If page not found display 404 error.
            $this->notFound();
        }

        // Post routing/controller hook.
        if (isset($this->hooks['after.run'])) {
            $this->hooks['after.run']->execute();
        }
    }

    /**
     * Send application response.
     *
     * @throws BadFunctionCallException
     * @return self
     */
    public function sendResponse()
    {
        if (!$this->isBooted) {
            throw new BadFunctionCallException('Error! Application is not booted.');
        }

        // Send final response.
        $this['response']->send();

        // Post response hook.
        if (isset($this->hooks['after.response'])) {
            $this->hooks['after.response']->execute();
        }

        // Display benchmark time if enabled.
        if ($this['config']['benchmark']) {
            print '<!--' . \PHP_Timer::resourceUsage() . '-->';
        }

        return $this;
    }

    /**
     * Default handler for 404 error.
     *
     * @param NotFoundException $e
     */
    protected function notFound(NotFoundException $e = null)
    {
        if (isset($this->hooks['not.found'])) {
            $this['not.found'] = $e;
            $this->hooks['not.found']->execute();
        } else {
            $this['response']->setStatusCode(404);
            $this['response']->setBody('<h1>404 Not Found</h1>The page that you have ' .
                'requested could not be found.');
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
            $this['exception'] = $e;
            $this->hooks['internal.error']->execute();
        } else {
            $this['response']->setStatusCode(500);
            $this['response']->setBody('Internal error: ' . $e->getMessage());
        }
    }

    /**
     * Add hook.
     *
     * @param string $key
     * @param string $class
     * @param string $function
     * @return self
     */
    public function setHook($key, $class, $function)
    {
        $this->hooks[$key] = new Executable($class, $function);
        return $this;
    }

    /**
     * Get hook.
     *
     * @param string $key
     * @return ExecutableInterface
     */
    public function getHook($key)
    {
        return $this->hooks[$key];
    }

    /**
     * @param string $class
     * @param string $function
     * @param array $params
     * @param string $namespacePrefix
     * @throws \InvalidArgumentException
     */
    protected function execute($class, $function, $params = [], $namespacePrefix = null)
    {
        $executable = new Executable($class, $function);

        if (!empty($params)) {
            $executable->setParams($params);
        }

        if ($namespacePrefix !== null) {
            $executable->getNamespacePrefix($namespacePrefix);
        }

        $executable->execute();
    }

    /**
     * @param string $controllerNamespace
     * @return self
     */
    public function setControllerNamespace($controllerNamespace)
    {
        $this->controllerNamespace = $controllerNamespace;
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
     * @param string $routesPath
     * @return self
     */
    public function setRoutesPath($routesPath)
    {
        $this->routesPath = $routesPath;
        return $this;
    }

    /**
     * @param string $configPath
     * @return self
     */
    public function setConfigPath($configPath)
    {
        $this->configPath = $configPath;
        return $this;
    }

    /**
     * @param string $viewsPath
     * @return self
     */
    public function setViewsPath($viewsPath)
    {
        $this->viewsPath = $viewsPath;
        return $this;
    }

    /**
     * @return string
     */
    public function getViewsPath()
    {
        return $this->viewsPath;
    }

    /**
     * @param string $databaseConfigPath
     * @return self
     */
    public function setDatabaseConfigPath($databaseConfigPath)
    {
        $this->databaseConfigPath = $databaseConfigPath;
        return $this;
    }

    /**
     * @param string $class
     * @param string $function
     * @return self
     */
    public function addMiddleware($class, $function)
    {
        $this->middleware[] = new Executable($class, $function);
        return $this;
    }

    /**
     * Get singleton instance of Core class.
     *
     * @param string $appPath
     * @return Core
     */
    public static function getInstance($appPath = '')
    {
        if (null === self::$instance) {
            self::$instance = new Core($appPath);
        }
        return self::$instance;
    }
}