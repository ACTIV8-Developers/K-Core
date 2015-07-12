<?php
namespace Core\Container;

use Core\Container\Interfaces\ContainerAwareInterface;

/**
 * Abstract class ServiceProvider.
 *
 * @author <milos@caenazzo.com>
 */
abstract class ServiceProvider implements ContainerAwareInterface
{
    /**
     * @var Container $app
     */
    protected $app = null;

    /**
     * Register the service provider(s).
     */
    abstract public function register();

    /**
     * @return Container
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * @param Container $app
     * @return self
     */
    public function setApp(Container $app)
    {
        $this->app = $app;
        return $this;
    }
}
