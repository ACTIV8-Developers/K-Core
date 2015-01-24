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
	 * @var object Core
	 */
	protected $app = null;

	/**
	 * Create a new service provider instance.
	 *
	 * @param Core $app
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
