<?php
namespace Core\Routing\Interfaces;

/**
 * ExecutableInterface
 *
 * @author <milos@caenazzo.com>
 */
interface ExecutableInterface
{
    /**
     * Execute action
     *
     * @param ResolverInterface
     * @return self
     */
    function execute(ResolverInterface $resolver);

    /**
     * @param array $params
     */
    public function setParams(array $params);

    /**
     * @param array $params
     */
    public function addParams(array $params);
}