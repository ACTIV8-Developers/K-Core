<?php
namespace Core\Session;

use Core\Container\ContainerAware;
use Core\Session\Handlers\DatabaseSessionHandler;

/**
 * Class SessionServiceProvider.
 *
 * @author <milos@caenazzo.com>
 */
class SessionServiceProvider extends ContainerAware
{
    /**
     * Create Session class.
     */
    public function __invoke()
    {
        // Create session class closure.
        $this->container['session'] = function ($c) {
            // Select session handler.
            $handler = null;
            switch ($c['config']['sessionHandler']) {
                case 'database':
                    $name = isset($c['config']['session']['connName'])?$c['config']['session']['connName']:'db';
                    $conn = $c[$name]->getConnection();
                    $handler = new DatabaseSessionHandler($conn);
                    if (isset($c['config']['session']['expiration'])) {
                        $handler->setGcLifetime($c['config']['session']['expiration']);
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