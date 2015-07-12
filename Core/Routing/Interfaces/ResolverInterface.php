<?php
namespace Core\Routing\Interfaces;

/**
 * ResolverInterface
 *
 * @author <milos@caenazzo.com>
 */
interface ResolverInterface
{
    /**
     * Execute action
     *
     * @param string
     * @return object
     */
    function resolve($classname);
}