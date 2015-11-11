<?php
namespace Core\Session;

use Core\Container\ServiceProvider;
use Core\Session\Handlers\DatabaseSessionHandler;
use Core\Session\Handlers\EncryptedFileSessionHandler;

/**
 * Class SessionServiceProvider.
 *
 * @author <milos@caenazzo.com>
 */
class SessionServiceProvider extends ServiceProvider
{
    /**
     * Create Session class.
     */
    public function register()
    {
        // Create session class closure.
        $this->container['session'] = function ($c) {
            // Select session handler.
            $handler = null;
            switch ($c['config']['sessionHandler']) {
                case 'encrypted-file':
                    $handler = new EncryptedFileSessionHandler();
                    break;
                case 'database':
                    $name = isset($c['config']['session']['connName'])?$c['config']['session']['connName']:'db';
                    $conn = $c[$name]->getConnection();
                    $handler = new DatabaseSessionHandler($conn);
                    if (isset($c['config']['session']['gcLifetime'])) {
                        $handler->setGcLifetime($c['config']['session']['gcLifetime']);
                    }
                    if (isset($c['config']['session']['tableName'])) {
                        $handler->setTableName($c['config']['session']['tableName']);
                    }
                    break;
            }
            $session = new Session($c['config']['session'], $handler);
            $session->setHashKey($c['config']['key']);
            return $session;
        };
    }
}