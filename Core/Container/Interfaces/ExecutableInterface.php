<?php
namespace Core\Container\Interfaces;

/**
 * ExecutableInterface
 *
 * @author <milos@caenazzo.com>
 */
interface ExecutableInterface extends ContainerAwareInterface
{
    /**
     * Execute action
     *
     * @return self
     */
    public function execute();

    /**
     * @param string $class
     * @return self
     */
    public function setClass($class);

    /**
     * @param string $method
     * @return self
     */
    public function setMethod($method);

    /**
     * @param array $params
     * @return self
     */
    public function setParams(array $params);
}