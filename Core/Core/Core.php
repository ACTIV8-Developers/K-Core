<?php 
namespace Core\Core;

use Exception;
use InvalidArgumentException;
use Core\Http\Request;
use Core\Http\Response;
use Core\Session\Session;
use Core\Database\Database;
use Core\Container\Container;
use Core\Routing\Action;
use Core\Routing\Router;
use Core\Routing\Interfaces\ActionInterface;
use Core\Core\Exceptions\StopException;
use Core\Core\Exceptions\NotFoundException;
use Core\Database\Connections\MySQLConnection;
use Core\Session\Handlers\DatabaseSessionHandler;
use Core\Session\Handlers\EncryptedFileSessionHandler;

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
    const VERSION = '1.5rc';

    /**
     * @var Core
     */
    protected static $instance;

    /**
     * Array of hooks to be applied.
     *
     * @var array
     */
    protected $hooks = [
        'before.boot'    => null, 
        'after.boot'     => null,
        'before.routing' => null, 
        'after.routing'  => null,
        'after.response' => null,
        'not.found'      => null,
        'internal.error' => null
    ];

    /**
     * Array of middleware actions
     * 
     * @var array
     */
    protected $middleware = [];

    /**
     * Class constructor.
     * Initializes framework and loads required classes.
     *
     * @throws \InvalidArgumentException
     */
    public function __construct()
    {
        // Invoke container construct
        parent::__construct();

        // Pre boot hook.
        if (isset($this->hooks['before.boot'])) {
            $this->executeAction($this->hooks['before.boot']);
        }

        // Fill container with required objects
        $this->boot();
        
        // Register service providers.
        if (isset($this['config']['services']) && is_array($this['config']['services'])) {
            foreach ($this['config']['services'] as $service) {
                $s = new $service();
                $s->register($this);
            }
        }

        // Register middleware stack
        if (isset($this['config']['middleware']) && is_array($this['config']['middleware'])) {
            $this->middleware = $this['config']['middleware'];
        }

        // After boot hook
        if (isset($this->hooks['after.boot'])) {
            $this->executeAction($this->hooks['after.boot']);
        }
    }

    /**
     * Fill container with required objects
     *
     * @throws \InvalidArgumentException
     */
    protected function boot()
    {
        // Load application configuration.
        $this['config'] = require APP.'/Config/Config.php';
        
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

        // Create router class closure
        $this['router'] = function() {
            return new Router();
        }; 

        // Load database configuration.
        $this['config.database'] = require APP.'/Config/Database.php';

        // For each needed database create database class closure.
        foreach ($this['config.database'] as $dbName => $dbConfig) {
            $this['db.'.$dbName] = function() use ($dbConfig) {
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
        $this['session'] = function($c) {
            // Select session handler.
            $handler = null;
            switch ($c['config']['sessionHandler']) {
                case 'encrypted-file':
                    $handler = new EncryptedFileSessionHandler();
                    break;
                case 'database':
                    $name = $c['config']['session']['connName'];
                    $conn = $this['db.'.$name]->getConnection();
                    $handler = new DatabaseSessionHandler($conn);
                    break;
            }
            $session = new Session($c['config']['session'], $handler);
            $session->setHashKey($c['config']['key']);
            return $session;
        };
    }
    
    /**
     * Application main executive function.
     */        
    public function run()
    {
        try {
            $this->routeRequest();
        } catch (StopException $e) {
            // Just pass and send current response
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
        // Get router object
        $route = $this['router'];

        // Collect routes list from file.
        include ROUTES;

        // Pre routing/controller hook.
        if (isset($this->hooks['before.routing'])) {
            $this->executeAction($this->hooks['before.routing']);
        }

        // Call middleware stack
        foreach ($this->middleware as $m) {
            $this->executeAction($m);
        }

        // Route requests
        $matchedRoute = $route->run($this['request']->getUri(), $this['request']->getMethod());

        // Execute route if found.
        if (false !== $matchedRoute) {
            // Append passed params to GET array.
            $this['request']->get->add($matchedRoute->params);

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
            $this->executeAction($this->hooks['after.routing']);
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
            $this->executeAction($this->hooks['after.response']);
        }

        // Display benchmark time if enabled.
        if ($this['config']['benchmark']) {
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
            $this['not.found'] = $e;
            $this->executeAction($this->hooks['not.found']);
        } else {
            $this['response']->setStatusCode(404);
            $this['response']->setBody('<h1>404 Not Found</h1>The page that you have '.
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
            $this->executeAction($this->hooks['internal.error']);
        } else {
            $this['response']->setStatusCode(500);                
            $this['response']->setBody('Internal error: '.$e->getMessage());
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
     * @param array|Action
     * @throws \InvalidArgumentException
     */
    public function executeAction($callable) 
    {
        if (is_array($callable)) {
            $action = new Action($callable[0], $callable[1]);
        } elseif($callable instanceof ActionInterface) {
            $action = $callable;
        } else {
            throw new InvalidArgumentException('Error! Callable must be instance of Action interface 
                or array containing two parameters');
        }

        $action->execute();
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
