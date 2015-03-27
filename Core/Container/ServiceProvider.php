<?php
namespace Core\Container;

/**
 * Abstract class ServiceProvider.
 * 
 * @author <milos@caenazzo.com>
 */
abstract class ServiceProvider 
{
	/**
	 * @var \Core\Container\Container
	 */
	protected $container = null;

	/**
	 * Create a new service provider instance.
	 *
	 * @param \Core\Container\Container
	 */
	public function __construct($container)
	{
		$this->container = $container;
	}

	/**
	 * Register the service provider(s).
	 */
	abstract public function register();
}
