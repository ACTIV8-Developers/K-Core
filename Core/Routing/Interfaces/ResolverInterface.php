<?php
namespace Core\Routing\Interfaces;

/**
 * ResolverInterface
 *
 * @author <milos@activ8.rs>
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