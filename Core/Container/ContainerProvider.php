<?php
namespace Core\Container;

use Core\Core\Core;

/**
 * Abstract class ContainerProvider. Extend to gain access to core container.
 *
 * @property Core $app
 * @author <milos@caenazzo.com>
 */
abstract class ContainerProvider
{
    /**
     * @param string $var
     * @return mixed
     */
    public function __get($var)
    {
        if ($var === 'app') {
            $this->app = Core::getInstance();
            return $this->app;
        } else {
            return $this->getValue($var);
        }
    }

    /**
     * Get value from container.
     *
     * @param string $key
     * @return mixed
     */
    protected function getValue($key)
    {
        if (isset($this->app[$key])) {
            return $this->app[$key];
        }
        return null;
    }

    /**
     * Set value in container.
     *
     * @param string $key
     * @param mixed $value
     */
    protected function setValue($key, $value)
    {
        $this->app[$key] = $value;
    }

    /**
     * Handle calls to missing methods on the controller.
     *
     * @param  string $method
     * @param  array $parameters
     * @throws BadMethodCallException
     */
    public function __call($method, array $parameters)
    {
        throw new BadMethodCallException("Method [$method] does not exist.");
    }
}
