<?php
namespace Core\Database;

use Core\Container\ServiceProvider;

/**
 * Class DatabaseServiceProvider.
 *
 * @author <milos@caenazzo.com>
 */
class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Create Database class.
     */
    public function register()
    {
        // Load database configuration.
        if (is_file($this->appPath . '/Config/Database.php')) {
            $container['config.database'] = require $this->appPath . '/Config/Database.php';
        } else {
            $container['config.database'] = [];
        }

        // For each needed database create database class closure.
        foreach ($this['config.database'] as $dbName => $dbConfig) {
            $container['db.' . $dbName] = function () use ($dbConfig) {
                $db = null;
                switch ($dbConfig['driver']) { // Choose connection and create it.
                    case 'mysql':
                        $db = new MySQLConnection($dbConfig);
                        break;
                    default:
                        throw new InvalidArgumentException('Error! Unsupported database connection type.');
                }
                // Inject it into database class.
                $database = new Database();
                $database->setConnection($db->connect());
                return $database;
            };
        }
    }
}