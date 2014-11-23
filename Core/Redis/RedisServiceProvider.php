<?php
namespace Core\Redis;


class RedisServiceProvider extends ServiceProvider
{
	/**
	* Create redis connection.
	*/
	public function register()
	{
		$this->app['redis'] = function() {
            return new \Predis\Client($c['config']['redis']);
        };
	}
}