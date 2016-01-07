<?php
namespace Core\Auth;

use Core\Container\ContainerAware;

/**
 * Class AuthServiceProvider.
 *
 * @author <milos@caenazzo.com>
 */
class AuthServiceProvider extends ContainerAware
{
    /**
     * Create Auth class.
     */
    public function __invoke()
    {
        $this->container['auth'] = function ($c) {
            return new Auth($c['db']->getConnection(), $c['session'], new PasswordHash(8, false));
        };
    }
}