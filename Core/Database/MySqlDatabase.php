<?php
namespace Core\Database;

use Core\Database\Interfaces\DatabaseInterface;

/**
 * Basic database class used for common MySQL CRUD operations.
 *
 * @author <milos@activ8.rs>
 */
class MySqlDatabase implements DatabaseInterface
{
    /**
     * Database connection.
     *
     * @var ?\PDO
     */
    protected ?\PDO $connection = null;

    /**
     * Get connection variable.
     *
     * @return \PDO
     */
    public function getConnection(): ?\PDO
    {
        return $this->connection;
    }

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
     * Begin database transaction.
     */
    public function beginTransaction()
    {
        $this->connection->beginTransaction();
    }

    /**
     * Commit database transaction.
     */
    public function commit()
    {
        $this->connection->commit();
    }

    /**
     * Rollback current database transaction.
     */
    public function rollback()
    {
        $this->connection->rollBack();
    }

    /**
     * Classic query method using prepared statements.
     *
     * @param string $query
     * @param array $params
     * @return \PDOStatement
     */
    public function query($query, array $params = []): \PDOStatement
    {
        // Execute query
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        // Return result resource variable
        return $stmt;
    }
    
    /**
     * Set PDO attribute.
     *
     * @param int $attr
     * @param mixed $value
     */
    public function setAttribute(int $attr, $value)
    {
        $this->connection->setAttribute($attr, $value);
    }
    
    /**
     * Select query.
     *
     * @param string $query
     * @param array $params
     * @param string $fetchMode
     * @return array
     */
    public function select(string $query, array $params = [], $fetchMode = null): array
    {
        // Execute query
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        if ($fetchMode !== null) {
            $stmt->setFetchMode($fetchMode);
        }
        return $stmt->fetchAll();
    }

    /**
     * Insert query.
     *
     * @param string $query
     * @param array $params
     * @return int
     */
    public function insert(string $query, array $params): int
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /**
     * Wrapper for PDO last insert id.
     *
     * @param string $name (optional)
     * @return int
     */
    public function lastInsertId($name = null): int
    {
        return $this->connection->lastInsertId($name);
    }

    /**
     * Update query.
     *
     * @param string $query
     * @param array $params
     * @return int
     */
    public function update(string $query, array $params): int
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /**
     * Delete query.
     *
     * @param string $query
     * @param array $params
     * @return int
     */
    public function delete(string $query, array $params = []): int
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /**
     * Count query.
     *
     * @param string $query
     * @param array $params
     * @return int
     */
    public function count(string $query, array $params = []): int
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    /**
     * Create table in database (MySQL specific).
     *
     * @param string $name
     * @param array $fields Fields array example ['id'=>'INT AUTO_INCREMENT PRIMARY KEY', 'value'=>'varchar(10)']
     * @param string $options (additional options for table like engine, UTF etc)
     * @return int
     */
    public function createTable(string $name, array $fields, $options = null): int
    {
        // Make query
        $sql = "CREATE TABLE IF NOT EXISTS $name (";
        foreach ($fields as $field => $type) {
            if (preg_match('/PRIMARY KEY/i', $type)) {
                $pk = $field;
                $type = str_replace('PRIMARY KEY', '', $type);
            }
            $sql .= "$field $type, ";
        }
        if (isset($pk)) {
            $sql = rtrim($sql, ",") . ' PRIMARY KEY (' . $pk . ')';
        } else {
            $sql = substr($sql, 0, strlen($sql) - 2);
        }
        if ($options === null) {
            $sql .= ") CHARACTER SET utf8 COLLATE utf8_general_ci;";
        } else {
            $sql .= ")" . $options;
        }

        // Execute query
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute();
    }

    /**
     * Add index to table column.
     *
     * @param string $table
     * @param string $column
     * @param string $name
     * @return bool
     */
    public function addIndex(string $table, string $column, string $name): bool
    {
        $sql = sprintf('ALTER TABLE %s ADD INDEX %s(%s)', $table, $name, $column);

        // Execute query
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute();
    }
}