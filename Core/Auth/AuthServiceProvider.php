<?php
namespace Core\Auth;


class AuthServiceProvider extends ServiceProvider
{
	/**
	* Create Auth class.
	*/
	public function register()
	{
		$this->app['auth'] = function() {
			return new Auth();
		}
	}
}