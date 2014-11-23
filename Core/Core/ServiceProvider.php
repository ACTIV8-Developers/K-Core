<?php
namespace Core\Core;

abstract class ServiceProvider 
{
	/**
	* @var object Core
	*/
	protected $app = null;


	/**
	* Create a new service provider instance.
	*
	* @param Core
	*/
	public function __construct($app)
	{
		$this->app = $app;
	}

	/**
	* Register the service provider(s).
	*/
	abstract public function register();
}
