<?php
namespace Core\Auth;

use Core\Core\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
	/**
	* Create Auth class.
	*/
	public function register()
	{
		$this->app['auth'] = function($c) {
			return new Auth($c['db.default'], $c['session']);
		};
	}
}