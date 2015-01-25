<?php
namespace Core\Core;

/**
 * Abstract class ServiceProvider.
 * 
 * @author <milos@caenazzo.com>
 */
abstract class ServiceProvider 
{
	/**
	 * @var \Core\Core\Core
	 */
	protected $app = null;

	/**
	 * Create a new service provider instance.
	 *
	 * @param \Core\Core\Core $app
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
