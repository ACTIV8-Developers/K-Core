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
        return $this->app['db.' . $dbName];
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
            return $this->app['db.default'];
        }
        if (isset($this->app[$key])) {
            return $this->app[$key];
        }
        return null;
    }
}