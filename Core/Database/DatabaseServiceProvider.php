<?php
namespace Core\Database;

use Core\Container\ContainerAware;
use Core\Database\Connections\MySQLConnection;

/**
 * Class DatabaseServiceProvider.
 *
 * @author <milos@caenazzo.com>
 */
class DatabaseServiceProvider extends ContainerAware
{
    /**
     * Create Database class.
     */
    public function __invoke()
    {
        // Load database configuration.
        if (is_file($this->container['basePath'] . '/Config/Database.php')) {
            $this->container['config.database'] = require $this->container['basePath'] . '/Config/Database.php';
        } else {
            $this->container['config.database'] = [];
        }

        // For each needed database create database class closure.
        foreach ($this->container['config.database'] as $dbName => $dbConfig) {
            if ($dbName === 'default') $dbName = '';
            $this->container['db' . $dbName] = function () use ($dbConfig) {
                $db = null;
                switch ($dbConfig['driver']) { // Choose connection and create it.
                    case 'mysql':
                        $db = new MySQLConnection($dbConfig);
                        break;
                    default:
                        throw new \InvalidArgumentException('Error! Unsupported database connection type.');
                }
                // Inject it into database class.
                $database = new Database();
                $database->setConnection($db->connect());
                return $database;
            };
        }
    }
}