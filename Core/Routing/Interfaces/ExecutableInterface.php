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
     * @return self
     */
    public function execute();
}