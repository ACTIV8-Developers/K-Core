<?php
namespace Core\Container\Interfaces;

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
     * @return self
     */
    public function execute();
}