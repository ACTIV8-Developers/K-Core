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
     * @var \PDO
     */
    protected $connection = null;

    /**
     * Set connection variable.
     *
     * @param \PDO $conn
     */
    public function setConnection(\PDO $conn)
    {
        $this->connection = $conn;
    }

    /**
     * Get connection variable.
     *
     * @return \PDO
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
     */
    public function setAttribute($attr, $value)
    {
    	$this->connection->setAttribute($attr, $value);
    }
}