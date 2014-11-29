<?php 
namespace Core\Core;

/**
* Base model abstract class.
* 
* @author Milos Kajnaco <miloskajnaco@gmail.com>
*/
abstract class Model extends ContainerProvider
{
    /**
    * Get database object.
    *
    * @param string
    * @return object \Core\Database\Database
    */
    protected function db($dbName = 'default')
    {
        return Core::getInstance()['db.'.$dbName ];
    }

    /**
    * Get value from container.
    *
    * @param string
    * @return mixed
    */
    protected function get($key)
    {
        if ($key === 'db') {
            return $this->app['db.default'];
        }
        if (isset($this->app[$key])) {
            return $this->app[$key];
        }
        return null;
    }
}