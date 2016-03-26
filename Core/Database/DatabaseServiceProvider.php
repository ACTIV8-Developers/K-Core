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
        if (isset($this->container['config']['dbConfigPath'])) {
            $this->container['config.database'] =
                include $this->container['config']['dbConfigPath'];
        } else {
            $this->container['config.database'] = [];
        }

        // For each needed database create database class closure.
        foreach ($this->container['config.database'] as $dbName => $dbConfig) {
            if ($dbName === 'default') $dbName = '';
            $this->container['db' . $dbName] = function () use ($dbConfig) {
                $db = null;
                switch ($dbConfig['driver']) {
                    case 'mysql':
                        $db = new MySQLConnection($dbConfig);
                        $database = new MySqlDatabase();
                        $database->setConnection($db->connect());
                        break;
                    default:
                        throw new \InvalidArgumentException('Error! Unsupported database connection type.');
                }
                return $database;
            };
        }
    }
}