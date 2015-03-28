<?php 
namespace Core\Core;

use Exception;
use InvalidArgumentException;
use Whoops\Handler\PrettyPageHandler;
use Core\Http\Request;
use Core\Http\Response;
use Core\Routing\Router;
use Core\Session\Session;
use Core\Database\Database;
use Core\Container\Container;
use Core\Core\Exceptions\NotFoundException;
use Core\Database\Connections\MySQLConnection;
use Core\Session\Handlers\DatabaseSessionHandler;
use Core\Session\Handlers\EncryptedFileSessionHandler;

/**
 * Core class.
 *
 * This is the heart of whole framework.
 * 
 * @author <milos@caenazzo.com>
 */
class Core
{
    /**
     * Core version.
     *
     * @var string
     */
    const VERSION = '1.5rc';

    /**
     * @var Core
     */
    protected static $instance;

    /**
     * Object container
     *
     * @var Container
     */
    protected $container = null;

    /**
     * Array of hooks to be applied.
     *
     * @var array
     */
    protected $hooks = [
        'before.system'  => null, 
        'after.system'   => null,
        'before.routing' => null, 
        'after.routing'  => null,
        'after.response' => null,
        'not.found'      => null,
        'internal.error' => null
    ];

    /**
     * Class constructor.
     * Initializes framework and loads required classes.
     *
     * @throws \InvalidArgumentException
     */
    protected function __construct()
    {
        // Get object container instance
        $this->container = new Container();

        // Pre system hook.
        if (isset($this->hooks['before.system'])) {
            call_user_func($this->hooks['before.system'], $this);
        }

        // Fill container with required objects
        $this->boot();
        
        // Register service providers.
        foreach ($this->container['config']['services'] as $service) {
            $s = new $service($this->container);
            $s->register();
        }

        // After system hook
        if (isset($this->hooks['after.system'])) {
            call_user_func($this->hooks['after.system'], $this);
        }
    }

    /**
     * Fill container with required objects
     */
    protected function boot()
    {
        // Load application configuration.
        $this->container['config'] = require APP.'/Config/Config.php';

        // Set error reporting.
        if ($this->container['config']['debug'] === true) {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
            if ($this->container['config']['whoops'] === true) {
                $this->container['whoops'] = function() {
                    return new \Whoops\Run();
                };
                $this->container['whoops']->pushHandler(new PrettyPageHandler());
                $this->container['whoops']->register();
            }
        } else {
            ini_set('display_errors', 'Off');
            error_reporting(0);
        }

        // Set default timezone.
        if (isset($this->container['config']['timezone'])) {
            date_default_timezone_set($this->container['config']['timezone']);
        }
        
        // Create request class closure.
        $this->container['request'] = function() {
            return new Request($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
        }; 

        // Create response class closure.
        $this->container['response'] = function($c) {
            $response = new Response();
            $response->setProtocolVersion($c['request']->getProtocolVersion());
            return $response;
        };

        // Load database configuration.
        $this->container['config.database'] = require APP.'/Config/Database.php';

        // For each needed database create database class closure.
        foreach ($this->container['config.database'] as $name => $dbConfig) {
            $this->container['db.'.$name] = function() use ($dbConfig) {
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
        $this->container['session'] = function($c) {
            // Select session handler.
            $handler = null;
            switch ($c['config']['sessionHandler']) {
                case 'encrypted-file':
                    $handler = new EncryptedFileSessionHandler();
                    break;
                case 'database':
                    $name = $c['config']['session']['connName'];
                    $conn = $this->container['db.'.$name]->getConnection();
                    $handler = new DatabaseSessionHandler($conn);
                    break;
            }
            $session = new Session($c['config']['session'], $handler);
            $session->setHashKey($c['config']['key']);
            return $session;
        };
    }
    
    /**
     * Framework main executive function.
     */        
    public function run()
    {
        // Load and start session if enabled in configuration.
        if ($this->container['config']['sessionStart']) {
            $this->container['session']->start();
        }

        // Execute routing.
        try {
            $this->routeRequest();
        } catch (NotFoundException $e) {
            $this->notFound($e);
        } catch (Exception $e) {
            $this->internalError($e);
        }

        // Send application response
        $this->sendResponse();
    }  

    /**
     * Route request and execute proper controller if route found.
     */
    public function routeRequest()
    {
        // Create router instance.
        $route = new Router();

        // Collect routes list from file.
        include ROUTES;

        // Pre routing/controller hook.
        if (isset($this->hooks['before.routing'])) {
            call_user_func($this->hooks['before.routing'], $this);
        }

        // Route requests
        $matchedRoute = $route->run($this->container['request']->getUri(), $this->container['request']->getMethod());

        // Execute route if found.
        if (false !== $matchedRoute) {
            // Append passed params to GET array.
            $this->container['request']->get->add($matchedRoute->params);

            // Execute matched route.
            $matchedRoute->action
                        ->setNamespacePrefix(CONTROLERS)
                        ->setParams($matchedRoute->params)
                        ->execute();

        } else {
            // If page not found display 404 error.
            $this->notFound();
        }

        // Post routing/controller hook.
        if (isset($this->hooks['after.routing'])) {
            call_user_func($this->hooks['after.routing'], $this);
        }
    }

    /**
     * Send application response.
     */
    public function sendResponse()
    {
        // Send final response.
        $this->container['response']->send();

        // Post response hook.
        if (isset($this->hooks['after.response'])) {
            call_user_func($this->hooks['after.response'], $this);
        }

        // Display benchmark time if enabled.
        if ($this->container['config']['benchmark']) {
            print '<!--'.\PHP_Timer::resourceUsage().'-->';
        }
    }

    /**
     * Default handler for 404 error.
     *
     * @param NotFoundException
     */
    public function notFound(NotFoundException $e = null)
    {
        if (isset($this->hooks['not.found'])) {
            $this->container['error'] = $e;
            call_user_func($this->hooks['not.found'], $this);
        } else {
            $this->container['response']->setStatusCode(404);
            $this->container['response']->setBody('<h1>404 Not Found</h1>The page that you have requested could not be found.');
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
            $this->container['error'] = $e;
            call_user_func($this->hooks['internal.error'], $this);
        } else {
            $this->container['response']->setStatusCode(500);                
            if ($this->container['config']['debug'] === true && $this->container['config']['whoops'] === true) {
                $this->container['whoops']->handleException($e);
            }
        }
    }

    /**
     * Add hook.
     *
     * @param string $key
     * @param callable $callable
     */
    public function setHook($key, $callable) 
    {
        $this->hooks[$key] = $callable;
    }

    /**
     * Get hook.
     *
     * @param string $key
     * @return callable
     */
    public function getHook($key) 
    {
        return $this->hooks[$key];
    }

    /**
     * Set container
     *
     * @param Container $container
     */
    public function setContainer($container) 
    {
        $this->container = $container;
    }

    /**
     * Get container.
     *
     * @return Container
     */
    public function getContainer() 
    {
        return $this->container;
    }

    /**
     * Get container content by key.
     *
     * @param string $key
     * @return mixed
     */
    public function container($key) 
    {
        return $this->container[$key];
    }

    /**
     * Get singleton instance of Core class.
     *
     * @return Core
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new Core();
        }
        return self::$instance;
    }

    /**
     * Get new instance of Container class.
     *
     * @return Container
     */
    public static function getNew()
    {
        return new Core();
    }
}
