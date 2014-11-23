<?php
namespace Core\Redis;

use Core\Core\ServiceProvider;

class RedisServiceProvider extends ServiceProvider
{
	/**
	* Create redis connection.
	*/
	public function register($c)
	{
		$this->app['redis'] = function() {
            return new \Predis\Client($c['config']['redis']);
        };
	}
}