<?php
namespace Core\Redis;

use Core\Core\ServiceProvider;

class RedisServiceProvider extends ServiceProvider
{
	/**
	* Create redis connection.
	*/
	public function register()
	{
		$this->app['redis'] = function($c) {
            return new \Predis\Client($c['config']['redis']);
        };
	}
}