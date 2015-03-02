<?php 
namespace Core\Core;

use Core\Http\Request;
use Core\Http\Response;
use Core\Routing\Router;
use Core\Session\Session;
use Core\Database\Database;
use \Pimple\Container;

/**
 * Core class.
 * This is the heart of whole framework. It is a singleton container for all main 
 * objects. This class contains main run method which handles life cycle of application.
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
    const VERSION = '1.40rc';

    /**
     * Singleton instance of Core.
     *
     * @var \Core\Core\Core
     */
    protected static $instance = null;

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
    public function __construct()
    {
        // Call parent container constructor.
        parent::__construct();

        // Load application configuration.
        $this['config'] = require APP.'/Config/Config.php';

        // Set error reporting.
        if ($this['config']['debug'] === true) {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
            if ($this['config']['whoops'] === true) {
                $this['whoops'] = function() {
                    return new \Whoops\Run();
                };
                $this['whoops']->pushHandler(new \Whoops\Handler\PrettyPageHandler());
                $this['whoops']->register();
            }
        } else {
            ini_set('display_errors', 'Off');
            error_reporting(0);
        }

        // Pre system hook.
        if (isset($this->hooks['before.system'])) {
            call_user_func($this->hooks['before.system'], $this);
        }

        // Set default timezone.
        if (isset($this['config']['timezone'])) {
            date_default_timezone_set($this['config']['timezone']);
        }
        
        // Create request class closure.
        $this['request'] = function() {
            return new Request($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
        }; 

        // Create response class closure.
        $this['response'] = function($c) {
            $response = new Response();
            $response->setProtocolVersion($c['request']->getProtocolVersion());
            return $response;
        };

        // Load database configuration.
        $this['config.database'] = require APP.'/Config/Database.php';

        // For each needed database create database class closure.
        foreach ($this['config.database'] as $name => $dbConfig) {
            $this['db.'.$name] = function() use ($dbConfig) {
                $db = null;
                switch ($dbConfig['driver']) { // Choose connection and create it.
                    case 'mysql':               
                        $db = new \Core\Database\Connections\MySQLConnection($dbConfig);
                        break;
                    default:
                        throw new \InvalidArgumentException('Error! Unsupported database connection type.');
                }
                // Inject it into database class.
                $database = new Database();
                $database->setConnection($db->connect());
                return $database;
            };  
        }

        // Create session class closure.
        $this['session'] = function($c) {
            // Select session handler.
            $handler = null;
            switch ($c['config']['sessionHandler']) {
                case 'encrypted-file':
                    $handler = new \Core\Session\Handlers\EncryptedFileSessionHandler();
                    break;
                case 'database':
                    $name = $c['config']['session']['connName'];
                    $conn = $this['db.'.$name]->getConnection();
                    $handler = new \Core\Session\Handlers\DatabaseSessionHandler($conn);
                    break;
            }
            $session = new Session($c['config']['session'], $handler);
            $session->setHashKey($c['config']['key']);
            return $session;
        };

        // Register service providers.
        foreach ($this['config']['services'] as $service) {
            $s = new $service($this);
            $s->register();
        }

        // After system hook
        if (isset($this->hooks['after.system'])) {
            call_user_func($this->hooks['after.system'], $this);
        }
    }
    
    /**
     * Framework main executive function.
     */        
    public function run()
    {
        try {
            // Load and start session if enabled in configuration.
            if ($this['config']['sessionStart']) {
                $this['session']->start();
            }

            // Execute routing.
            $this->routeRequest();

        } catch (NotFoundException $e) {
            $this->notFound($e);
        } catch (\Exception $e) {
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
        $matchedRoute = $route->run($this['request']->getUri(), $this['request']->getMethod());

        // Execute route if found.
        if (false !== $matchedRoute) {
            // Append passed params to GET array.
            $this['request']->get->add($matchedRoute->params);

            // Execute matched route.
            $matchedRoute->action->setNamespacePrefix(CONTROLERS);
            $matchedRoute->action->execute();

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
        $this['response']->send();

        // Post response hook.
        if (isset($this->hooks['after.response'])) {
            call_user_func($this->hooks['after.response'], $this);
        }

        // Display benchmark time if enabled.
        if ($this['config']['benchmark']) {
            print '<!--'.\PHP_Timer::resourceUsage().'-->';
        }
    }

    /**
     * Default handler for 404 error.
     *
     * @param \Core\Core\NotFoundException
     */
    public function notFound(NotFoundException $e = null)
    {
        if (isset($this->hooks['not.found'])) {
            $this['error'] = $e;
            call_user_func($this->hooks['not.found'], $this);
        } else {
            $this['response']->setStatusCode(404);
            $this['response']->setContent('<h1>404 Not Found</h1>The page that you have requested could not be found.');
        }
    }

    /**
     * Handle exception.
     *
     * @param \Exception
     */
    protected function internalError(\Exception $e)
    {
        if (isset($this->hooks['internal.error'])) {
            $this['error'] = $e;
            call_user_func($this->hooks['internal.error'], $this);
        } else {
            $this['response']->setStatusCode(500);                
            if ($this['config']['debug'] === true && $this['config']['whoops'] === true) {
                $this['whoops']->handleException($e);
            }
        }
    }

    /**
     * Get singleton instance of Core class.
     *
     * @return \Core\Core\Core
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new Core();
        }
        return self::$instance;
    }

    /**
     * Get new instance of Core class.
     *
     * @return \Core\Core\Core
     */
    public static function getNew()
    {
        return new Core();
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
}