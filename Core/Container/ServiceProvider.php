<?php
namespace Core\Container;

/**
 * Abstract class ServiceProvider.
 *
 * @author <milos@caenazzo.com>
 */
abstract class ServiceProvider extends ContainerAware
{
    /**
     * Register the service provider(s).
     */
    abstract public function register();
}
