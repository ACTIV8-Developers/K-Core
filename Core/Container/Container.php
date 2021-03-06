<?php
namespace Core\Container;

use Core\Core\Resolver;
use Core\Http\Request;
use Core\Routing\Router;
use InvalidArgumentException;
use Pimple\Container as PimpleContainer;
use Psr\Container\ContainerInterface;

/**
 * Container
 *
 * @author <milos@activ8.rs>
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
        if (!isset($this['configPath'])) {
            $config = include $basePath . '/Config/Config.php';
        }

        // Set base path
        $config['basePath'] = $basePath;

        // Set app routes path
        if (!isset($config['routesPath'])) {
            $config['routesPath'] = $basePath . '/routes.php';
        }

        // Set path where views are stored
        if (!isset($config['viewsPath'])) {
            $config['viewsPath'] = $basePath . '/Views';
        }

        // Set path where database configuration is stored
        if (!isset($config['dbConfigPath'])) {
            $config['dbConfigPath'] = $basePath . '/Config/Database.php';
        }

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