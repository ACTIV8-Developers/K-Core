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
     * Register the service provider(s).
     *
     * @var \Core\Container\Container $container
     */
    abstract public function register($container);
}
