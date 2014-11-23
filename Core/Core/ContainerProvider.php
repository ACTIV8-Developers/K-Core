<?php
namespace Core\Core;

/**
* Abstract class ContainerProvider. Extend to gain acess to core container.
*
* @author Milos Kajnaco <miloskajnaco@gmail.com>
*/
abstract class ContainerProvider
{
    /**
    * Set value in container.
    *
    * @param string
    * @param mixed
    */
    protected function set($key, $value)
    {
        $this->app[$key] = $value;
    }

    /**
    * Get value from container.
    *
    * @param string
    * @return mixed
    */
    protected function get($key)
    {
        if (isset($this->app[$key])) {
            return $this->app[$key];
        }
        return null;
    }

    /**
    * @param string
    * @return mixed
    */
	public function __get($var) {
		if ($var === 'app') {
			$this->app = \Core\Core\Core::getInstance();
			return $this->app;
		} else {
			return $this->get($var);
		}
	}
}
