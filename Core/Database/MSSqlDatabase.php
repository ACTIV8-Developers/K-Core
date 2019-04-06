<?php
namespace Core\Database;

use Core\Database\Interfaces\DatabaseInterface;

/**
 * Basic database class used for common MSSQl CRUD operations.
 *
 * @author <milos@caenazzo.com>
 */
class MSSqlDatabase implements DatabaseInterface
{
    private $cTransID;
    private $childTrans = array();

    /**
     * Database connection.
     *
     * @var \PDO
     */
    protected $connection = null;

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

        $cAlphanum = "AaBbCc0Dd1EeF2fG3gH4hI5iJ6jK7kLlM8mN9nOoPpQqRrSsTtUuVvWwXxYyZz";
        $this->cTransID = "T".substr(str_shuffle($cAlphanum), 0, 7);

        array_unshift($this->childTrans, $this->cTransID);

        $stmt = $this->db->prepare("BEGIN TRAN [$this->cTransID];");
        return $stmt->execute();

    }

    /**
     * Commit database transaction.
     */
    public function commit()
    {
        while(count($this->childTrans) > 0){
            $cTmp = array_shift($this->childTrans);
            $stmt = $this->db->prepare("COMMIT TRAN [$cTmp];");
            $stmt->execute();
        }

        return  $stmt;
    }

    /**
     * Rollback current database transaction.
     */
    public function rollback()
    {
        while(count($this->childTrans) > 0){
            $cTmp = array_shift($this->childTrans);
            $stmt = $this->db->prepare("ROLLBACK TRAN [$cTmp];");
            $stmt->execute();
        }

        return $stmt;
    }

    /**
     * Classic query method using prepared statements.
     *
     * @param string $query
     * @param array $params
     * @return \PDOStatement|resource
     */
    public function query($query, array $params = [])
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
    public function setAttribute($attr, $value)
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
    public function select($query, array $params = [], $fetchMode = null)
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
    public function insert($query, array $params)
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
    public function lastInsertId($name = null)
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
    public function update($query, array $params)
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
    public function delete($query, array $params = [])
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
    public function count($query, array $params = [])
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    /**
     * Create table in database (MySQL specific).
     *
     * @param string $name
     * @param array $fields Fields array example ['id'=>'INT IDENTITY(1,1) PRIMARY KEY', 'value'=>'varchar(10)']
     * @param string $options (additional options for table like engine, UTF etc)
     * @return int
     */
    public function createTable($name, array $fields, $options = null)
    {
        // Make query
        $sql = "CREATE TABLE $name (";
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
        if ($options !== null) {
            $sql .= $options. ");";
        } else {
            $sql .= ");";
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
    public function addIndex($table, $column, $name)
    {
        $sql = sprintf('ALTER TABLE %s ADD INDEX %s(%s)', $table, $name, $column);

        // Execute query
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute();
    }
}