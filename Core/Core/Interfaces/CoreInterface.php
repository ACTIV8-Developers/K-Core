<?php
namespace Core\Core\Interfaces;

/**
 * Interface CoreInterface
 *
 * @author <milos@caenazzo.com>
 */
interface CoreInterface
{
    /**
     * Boot application
     *
     * @return self
     */
    public function boot();

    /**
     * Route request and execute associated action.
     *
     * @throws BadFunctionCallException
     * @return self
     */
    public function execute();

    /**
     * Send application response.
     *
     * @throws BadFunctionCallException
     * @return self
     */
    public function sendResponse();
}