<?php
namespace Core\Routing\Interfaces;

/**
 * RouterInterface
 *
 * @author <milos@caenazzo.com>
 */
interface RouterInterface
{
	/**
	 * Check routes and returns matching one if found,
     * otherwise return false.
     *
	 * @var string $uri
     * @var string $requestMethod
	 * @return bool|\Core\Routing\Route
	 */
	public function run($uri, $requestMethod);
}