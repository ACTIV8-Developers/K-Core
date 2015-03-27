<?php
namespace Core\Container;

use Pimple\Container as PimpleContainer;

/**
 * Container
 *
 * @author <milos@caenazzo.com>
 */
class Container extends PimpleContainer
{
	/**
	 * @var Container
	 */
	protected static $instance;

	/**
	 * Get singleton instance of Container class.
	 *
	 * @return Container
	 */
	public static function getInstance()
	{
		if (null === self::$instance) {
			self::$instance = new Container();
		}
		return self::$instance;
	}

	/**
	 * Get new instance of Container class.
	 *
	 * @return Container
	 */
	public static function getNew()
	{
		return new Core();
	}
}