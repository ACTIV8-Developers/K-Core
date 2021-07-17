<?php
namespace Core\Container;

use Core\Container\Interfaces\ContainerAwareInterface;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;

/**
 * Abstract class ContainerAware
 *
 * @author <milos@activ8.rs>
 */
abstract class ContainerAware implements ContainerAwareInterface
{
    /**
     * @var ?ContainerInterface $container
     */
    protected ?ContainerInterface $container = null;

    /**
     * @param ContainerInterface $container
     * @return self
     */
    public function setContainer(ContainerInterface $container): ContainerAwareInterface
    {
        $this->container = $container;
        return $this;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
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
