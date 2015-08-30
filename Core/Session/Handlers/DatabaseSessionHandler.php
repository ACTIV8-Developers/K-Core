<?php
namespace Core\Session\Handlers;

/**
 * DatabaseSessionHandler using database interface.
CREATE TABLE session_storage (
session_id CHAR(32) NOT NULL,
session_data TEXT NOT NULL,
session_lastaccesstime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (session_id)
);
 */
class DatabaseSessionHandler implements \SessionHandlerInterface
{
    /**
     * PDO connection.
     * @var \PDO
     */
    protected $db = null;

    /**
     * Table name if storage system is database
     *
     * @var string
     */
    protected $tableName = 'session_storage';

    /**
     * @var int|null
     */
    protected $gcLifetime = null;

    /**
     * @param string $tableName
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    /**
     * @param int|null $gcLifetime
     */
    public function setGcLifetime($gcLifetime)
    {
        $this->gcLifetime = $gcLifetime;
    }

    /**
     * @param \PDO
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Open the session
     *
     * @param string $save_path
     * @param string $session_id
     * @return \PDOStatement
     */
    public function open($save_path, $session_id)
    {
        $query = "INSERT INTO ". $this->tableName  ." SET session_id = :id, session_data = '' ON DUPLICATE KEY UPDATE session_lastaccesstime = NOW()";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $session_id]);
    }

    /**
     * Close the session
     * @return bool
     */
    public function close()
    {
        return true;
    }

    /**
     * Read the session
     *
     * @param int $id
     * @return string string of the session
     */
    public function read($id)
    {
        $query = "SELECT session_data FROM ". $this->tableName  ." WHERE session_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchColumn();
    }

    /**
     * @param string $id
     * @param string $data
     * @return array
     */
    public function write($id, $data)
    {
        $query = "INSERT INTO ". $this->tableName  ." SET session_id = :id, session_data = :data ON DUPLICATE KEY UPDATE session_data = :data";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id, 'data' => $data]);
    }

    /**
     * Destroy the session
     *
     * @param int". $this->tableName  ."id
     * @return bool
     */
    public function destroy($id)
    {
        $query = "DELETE FROM ". $this->tableName  ." WHERE session_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);

        setcookie(session_name(), '', time() - 3600);
    }

    /**
     * Garbage Collector
     *
     * @param int life time (sec.)
     * @return bool
     * @see session.gc_divisor 100
     * @see session.gc_maxlifetime 1440
     * @see session.gc_probability 1
     * @usage execution rate 1/100
     * (session.gc_probability/session.gc_divisor)
     */
    public function gc($max)
    {
        if ($this->gcLifetime !== null) {
            $max = $this->gcLifetime;
        }
        if ($max == 0) {
            return;
        }
        $sql = "DELETE FROM ". $this->tableName  ." WHERE session_lastaccesstime < DATE_SUB(NOW(), INTERVAL " . $max . " SECOND)";
        $this->db->query($sql);
    }
}