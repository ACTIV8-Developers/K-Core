<?php
namespace Core\Database\Interfaces;

/**
 * DatabaseInterface
 *
 * @author <milos@caenazzo.com>
 */
interface DatabaseInterface
{
    /**
     * @param string $query
     * @param array $params
     * @return resource
     */
    public function query($query, array $params = []);
}