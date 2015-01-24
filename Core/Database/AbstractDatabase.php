<?php
namespace Core\Database;

/**
 * AbstractDatabase class.
 *
 * @author <milos@caenazzo.com>
 */
abstract class AbstractDatabase
{
    /**
     * Database connection.
     *
     * @var object \PDO
     */
    protected $connection = null;

    /**
     * Set connection variable.
     *
     * @param object \PDO $conn
     */
    public function setConnection(\PDO $conn)
    {
        $this->connection = $conn;
    }

    /**
     * Get connection variable.
     *
     * @return object \PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Set PDO attribute.
     *
     * @param int $attr
     * @param mixed $value
     * @return bool
     */
    public function setAttribute($attr, $value)
    {
    	$this->connection->setAttribute($attr, $value);
    }
}