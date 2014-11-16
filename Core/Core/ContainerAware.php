<?php
namespace Core\Core;

/**
* Abstract class ContainerAware. Extend to gain acess to app core.
* Every called controller should be instance of ContainerAware.
*
* @author Milos Kajnaco <miloskajnaco@gmail.com>
*/
abstract class ContainerAware
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
