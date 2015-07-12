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
}