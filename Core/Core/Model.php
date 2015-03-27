<?php 
namespace Core\Core;

use Core\Container\ContainerProvider;

/**
 * Base model abstract class.
 * 
 * @author <milos@caenazzo.com>
 */
abstract class Model extends ContainerProvider
{
    /**
     * Get database object.
     *
     * @param string $dbName
     * @return \Core\Database\Database
     */
    protected function db($dbName = 'default')
    {
        return $this->container['db.'.$dbName];
    }

    /**
     * Get value from container.
     *
     * @param string $key
     * @return mixed
     */
    protected function getValue($key)
    {
        if ($key === 'db') {
            return $this->container['db.default'];
        }
        if (isset($this->container[$key])) {
            return $this->container[$key];
        }
        return null;
    }
}