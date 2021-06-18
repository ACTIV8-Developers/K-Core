<?php
namespace Core\Container\Interfaces;

use Psr\Container\ContainerInterface;

/**
 * Interface ContainerAwareInterface
 *
 * @author <milos@activ8.rs>
 */
interface ContainerAwareInterface
{
    /**
     * @param ContainerInterface $app
     * @return self
     */
    public function setContainer(ContainerInterface $app): ContainerAwareInterface;

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface;
}