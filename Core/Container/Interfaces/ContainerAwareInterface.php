<?php
namespace Core\Container\Interfaces;

use Psr\Container\ContainerInterface;

/**
 * Interface ContainerAwareInterface
 *
 * @author <milos@caenazzo.com>
 */
interface ContainerAwareInterface
{
    /**
     * @param ContainerInterface $app
     * @return self
     */
    public function setContainer(ContainerInterface $app);

    /**
     * @return Container
     */
    public function getContainer();
}