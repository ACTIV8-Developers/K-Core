<?php
namespace Core\Auth;

use Core\Container\ServiceProvider;

/**
 * Class AuthServiceProvider.
 *
 * @author <milos@caenazzo.com>
 */
class AuthServiceProvider extends ServiceProvider
{
	/**
	* Create Auth class.
	*/
	public function register()
	{
		$this->container['auth'] = function($c) {
			return new Auth($c['db.default'], $c['session']);
		};
	}
}