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

    /**
     * Get connection variable.
     *
     * @return \PDO
     */
    public function getConnection();

    /**
     * Set connection variable.
     *
     * @param \PDO $conn
     */
    public function setConnection(\PDO $conn);

    /**
     * Begin database transaction.
     */
    public function beginTransaction();

    /**
     * Commit database transaction.
     */
    public function commit();

    /**
     * Rollback current database transaction.
     */
    public function rollback();

    /**
     * Select query.
     *
     * @param string $query
     * @param array $params
     * @param string $fetchMode
     * @return array
     */
    public function select($query, array $params = [], $fetchMode = null);

    /**
     * Insert query.
     *
     * @param string $query
     * @param array $params
     * @return int
     */
    public function insert($query, array $params);

    /**
     * Wrapper for PDO last insert id.
     *
     * @param string $name (optional)
     * @return int
     */
    public function lastInsertId($name = null);

    /**
     * Update query.
     *
     * @param string $query
     * @param array $params
     * @return int
     */
    public function update($query, array $params);

    /**
     * Delete query.
     *
     * @param string $query
     * @param array $params
     * @return int
     */
    public function delete($query, array $params = []);

    /**
     * Count query.
     *
     * @param string $query
     * @param array $params
     * @return int
     */
    public function count($query, array $params = []);

    /**
     * Create table in database (MySQL specific).
     *
     * @param string $name
     * @param array $fields Fields array example ['id'=>'INT IDENTITY(1,1) PRIMARY KEY', 'value'=>'varchar(10)']
     * @param string $options (additional options for table like engine, UTF etc)
     * @return int
     */
    public function createTable($name, array $fields, $options = null);

    /**
     * Add index to table column.
     *
     * @param string $table
     * @param string $column
     * @param string $name
     * @return bool
     */
    public function addIndex($table, $column, $name);
}