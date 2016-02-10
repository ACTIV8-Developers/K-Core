<?php
namespace Core\Container;

use Core\Core\Resolver;
use Core\Http\Request;
use Core\Routing\Router;
use InvalidArgumentException;
use Interop\Container\ContainerInterface;
use Pimple\Container as PimpleContainer;

/**
 * Container
 *
 * @author <milos@caenazzo.com>
 */
class Container extends PimpleContainer implements ContainerInterface
{
    /**
     * Container constructor.
     * @param string $basePath
     * @param array $values
     */
    public function __construct($basePath = '', array $values = [])
    {
        parent::__construct($values);

        // Load application configuration.
        if (is_file($basePath . '/Config/Config.php')) {
            $config = include $basePath . '/Config/Config.php';
        } else {
            $config = [];
        }

        // Set base path
        $config['basePath'] = $basePath;

        // Set app routes path
        $config['routesPath'] = $basePath . '/routes.php';

        // Set path where views are stored
        $config['viewsPath'] = $basePath . '/Views';
        
        // Store config
        $this['config'] = $config;

        // Create executable resolver
        $this['resolver'] = function ($c) {
            return new Resolver($c);
        };

        // Create request class closure.
        $this['request'] = function () {
            return new Request($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
        };

        // Create router class closure
        $this['router'] = function () {
            return new Router();
        };
    }

    /**
     * Get entry from container
     *
     * @param string $key
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function get($key)
    {
        if (!$this->offsetExists($key)) {
            throw new InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $key));
        }
        return $this->offsetGet($key);
    }

    /**
     * Check for existence in container
     *
     * @param string $key
     * @return boolean
     */
    public function has($key)
    {
        return $this->offsetExists($key);
    }
}