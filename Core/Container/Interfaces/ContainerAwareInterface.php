<?php
namespace Core\Container\Interfaces;

use Core\Container\Container;

/**
 * Interface ContainerAwareInterface
 *
 * @author <milos@caenazzo.com>
 */
interface ContainerAwareInterface
{
    /**
     * @param Container $app
     * @return self
     */
    public function setContainer(Container $app);

    /**
     * @return Container
     */
    public function getContainer();
}