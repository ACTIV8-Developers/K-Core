<?php
namespace Core\Database\Interfaces;

/**
 * DatabaseInterface
 *
 * @author <milos@activ8.rs>
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
    public function getConnection(): ?\PDO;

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
    public function select(string $query, array $params = [], $fetchMode = null): array;

    /**
     * Insert query.
     *
     * @param string $query
     * @param array $params
     * @return int
     */
    public function insert(string $query, array $params): int;

    /**
     * Wrapper for PDO last insert id.
     *
     * @param string $name (optional)
     * @return int
     */
    public function lastInsertId($name = null): int;

    /**
     * Update query.
     *
     * @param string $query
     * @param array $params
     * @return int
     */
    public function update(string $query, array $params): int;

    /**
     * Delete query.
     *
     * @param string $query
     * @param array $params
     * @return int
     */
    public function delete(string $query, array $params = []): int;

    /**
     * Count query.
     *
     * @param string $query
     * @param array $params
     * @return int
     */
    public function count(string $query, array $params = []): int;

    /**
     * Create table in database (MySQL specific).
     *
     * @param string $name
     * @param array $fields Fields array example ['id'=>'INT IDENTITY(1,1) PRIMARY KEY', 'value'=>'varchar(10)']
     * @param string $options (additional options for table like engine, UTF etc)
     * @return int
     */
    public function createTable(string $name, array $fields, $options = null): int;

    /**
     * Add index to table column.
     *
     * @param string $table
     * @param string $column
     * @param string $name
     * @return bool
     */
    public function addIndex(string $table, string $column, string $name): bool;
}