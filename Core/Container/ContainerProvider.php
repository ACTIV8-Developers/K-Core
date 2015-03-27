<?php
namespace Core\Container;

/**
 * Abstract class ContainerProvider. Extend to gain access to core container.
 *
 * @author <milos@caenazzo.com>
 */
abstract class ContainerProvider
{
    /**
     * Set value in container.
     *
     * @param string $key
     * @param mixed $value
     */
    protected function setValue($key, $value)
    {
        $this->container[$key] = $value;
    }

    /**
     * Get value from container.
     *
     * @param string $key
     * @return mixed
     */
    protected function getValue($key)
    {
        if (isset($this->container[$key])) {
            return $this->container[$key];
        }
        return null;
    }

    /**
     * @param string $var
     * @return mixed
     */
    public function __get($var) 
    {
        if ($var === 'container') {
            $this->container = Container::getInstance();
            return $this->container;
        } else {
            return $this->getValue($var);
        }
    }
}
