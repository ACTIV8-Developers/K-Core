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
     * @var Container $app
     */
    protected $app = null;

    /**
     * @return Container
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * @param Container $app
     */
    public function setApp(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Register the service provider(s).
     */
    abstract public function register();
}
