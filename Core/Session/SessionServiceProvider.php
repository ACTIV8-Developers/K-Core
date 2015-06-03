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
        $this->app['session'] = function ($c) {
            // Select session handler.
            $handler = null;
            switch ($c['config']['sessionHandler']) {
                case 'encrypted-file':
                    $handler = new EncryptedFileSessionHandler();
                    break;
                case 'database':
                    $name = $c['config']['session']['connName'];
                    $conn = $this['db.' . $name]->getConnection();
                    $handler = new DatabaseSessionHandler($conn);
                    break;
            }
            $session = new Session($c['config']['session'], $handler);
            $session->setHashKey($c['config']['key']);
            return $session;
        };
    }
}