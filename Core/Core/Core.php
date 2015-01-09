<?php 
namespace Core\Core;

use \Core\Http\Request;
use \Core\Http\Response;
use \Core\Routing\Router;
use \Core\Session\Session;
use \Core\Database\Database;

/**
* Core class.
* This is the heart of whole framework. It is a singleton container for all main 
* objects of application. This class containes main 
* run method which handles life cycle of application.
* 
* @author Milos Kajnaco <miloskajnaco@gmail.com>
*/
class Core extends \Pimple\Container
{
    /**
    * Core version.
    *
    * @var string
    */
    const VERSION = '1.38b';

    /**
    * Singleton instance of Core.
    *
    * @var object
    */
    protected static $instance = null;

    /**
    * Array of hooks to be applied.
    *
    * @var array
    */
    protected $hooks = [
        'before.system'  => null, 
        'before.routing' => null, 
        'after.routing'  => null,
        'after.system'   => null,
        'not.found'      => null,
        'internal.error' => null
    ];

    /**
    * Class constructor.
    * Initializes framework and loads needed classes.
    *
    * @throws \InvalidArgumentException
    */
    public function __construct()
    {
        // Call parent container constructor.
        parent::__construct();

        // Load application configuration.
        $this['config'] = require APP.'Config/Config.php';

        // Set error reporting.
        if ($this['config']['debug'] === true) {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
            if ($this['config']['whoops'] === true) {
                $whoops = new \Whoops\Run();
                $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
                $whoops->register();
            }
        } else {
            ini_set('display_errors', 'Off');
            error_reporting(0);
        }

        // Set default timezone.
        date_default_timezone_set($this['config']['timezone']);

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
        $this['config.database'] = require APP.'Config/Database.php';

        // For each needed database create database class closure.
        foreach ($this['config.database'] as $name => $dbConfig) {
            $this['db.'.$name] = function($c) use ($dbConfig) {
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
                    $handler = new \Core\Session\Handlers\DatabaseSessionHandler();
                    break;
                case 'redis':
                    try {                       
                        $handler = new \Core\Session\Handlers\RedisSessionHandler($c['redis']);
                        $handler->prefix = $c['config']['session']['name'];
                        $handler->expire = $c['config']['session']['expiration'];
                    } catch(\Exception $e) {
                        throw new \InvalidArgumentException('Error!'.$e->getMessage());
                    }
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
        try {    
            // Pre system hook.
            if (isset($this->hooks['before.system'])) {
                call_user_func($this->hooks['before.system'], $this);
            }

            // Register service providers.
            foreach ($this['config']['services'] as $service) {
                $s = new $service($this);
                $s->register();
            }

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

        // Post routing/controller hook.
        if (isset($this->hooks['after.routing'])) {
            call_user_func($this->hooks['after.routing'], $this);
        }

        // Send final response.
        $this['response']->send();

        // Post response hook.
        if (isset($this->hooks['after.system'])) {
            call_user_func($this->hooks['after.system'], $this);
        }

        // Display benchmark time if enabled.
        if ($this['config']['benchmark']) {
            print '<!--'.\PHP_Timer::resourceUsage().'-->';
        }
    }  

    /**
    * Route request and execute proper controller if route found.
    */
    protected function routeRequest()
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
            // Write passed params to GET array.
            $this['request']->get->add($matchedRoute->params);

            // Get controller name with namespace prefix.
            $matchedRoute->controller = CONTROLERS.'\\'.$matchedRoute->controller;

            // Call controller method.
            call_user_func_array([new $matchedRoute->controller, $matchedRoute->method], $matchedRoute->params);
        } else {
            // If page not found display 404 error.
            $this->notFound();
        }
    }

    /**
    * Default handler for 404 error.
    *
    * @param object \NotFoundException
    */
    protected function notFound(NotFoundException $e = null)
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
    * Handle error.
    *
    * @param object \Exception
    */
    protected function internalError(\Exception $e)
    {
        if (isset($this->hooks['internal.error'])) {
            $this['error'] = $e;
            call_user_func($this->hooks['internal.error'], $this);
        } else {
            $this['response']->setStatusCode(500);                
            if ($this['config']['debug'] === true) {
                $this['response']->setContent($this->printException($e));
            }
        }
    }

    /**
    * Print exception to string.
    *
    * @param object \Exception
    * @return string
    */
    protected function printException(\Exception $e)
    {
        $out = '<pre><div style="color:red">';
        $out .= '<h2>Error: '.$e->getMessage().'</h2>';
        $out .= '<h3>#Line: '.$e->getLine().'</h3>';
        $out .= '<h3>#File: '.$e->getFile().'</h3>';
        $stack = $e->getTrace();

        foreach ($stack as $s) {
            $out .= '<ul>';
            foreach ($s as $msg) {
                if (is_string($msg)) {
                    $out .= '<li>'.$msg.'</li>';
                }
            }
            $out .= '</ul>';
        }
        
        return '</div></pre>'.$out;
    }

    /**
    * Get singleton instance of Core class.
    *
    * @return object Core
    */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new Core();
        }
        return self::$instance;
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