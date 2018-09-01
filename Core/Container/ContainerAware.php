<?php
namespace Core\Container;

use Core\Container\Interfaces\ContainerAwareInterface;
use InvalidArgumentException;

/**
 * Abstract class ContainerAware
 *
 * @author <milos@caenazzo.com>
 */
abstract class ContainerAware implements ContainerAwareInterface
{
    /**
     * @var Container $container
     */
    protected $container = null;

    /**
     * @param Container $container
     * @return self
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param $var
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function __get($var)
    {
        return $this->container->get($var);
    }
}
