<?php
namespace Core\Container\Interfaces;

use Interop\Container\ContainerInterface;

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
     * @return ContainerInterface
     */
    public function getContainer();
}