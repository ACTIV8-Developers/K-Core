<?php
namespace Core\Routing\Interfaces;

/**
 * ActionInterface
 *
 * @author <milos@caenazzo.com>
 */
interface ActionInterface
{
    /**
     * Execute action
     * @return self
     */
    public function execute();
}