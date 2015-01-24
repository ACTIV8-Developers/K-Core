<?php
namespace Core\Core;

use Core\Core\Core;

/**
 * Abstract class ContainerProvider. Extend to gain acess to core container.
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
        $this->app[$key] = $value;
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
}
