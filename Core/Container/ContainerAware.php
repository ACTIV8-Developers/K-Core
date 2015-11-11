<?php
namespace Core\Container;

use InvalidArgumentException;
use Interop\Container\ContainerInterface;
use Core\Container\Interfaces\ContainerAwareInterface;

/**
 * Abstract class ContainerAware
 *
 * @author <milos@caenazzo.com>
 */
abstract class ContainerAware implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface $app
     */
    protected $container = null;

    /**
     * @param ContainerInterface $container
     * @return self
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * @return ContainerInterface
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
