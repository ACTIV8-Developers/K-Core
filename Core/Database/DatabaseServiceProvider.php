<?php
namespace Core\Database;

use Core\Container\ServiceProvider;
use Core\Database\Connections\MySQLConnection;

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
        if (is_file($this->app->getconfigPath() . '/Database.php')) {
            $this->app['config.database'] = require $this->app->getconfigPath() . '/Database.php';
        } else {
            $this->app['config.database'] = [];
        }

        // For each needed database create database class closure.
        foreach ($this->app['config.database'] as $dbName => $dbConfig) {
            if ($dbName === 'default') $dbName = '';
            $this->app['db' . $dbName] = function () use ($dbConfig) {
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