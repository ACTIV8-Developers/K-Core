<?php
namespace Core\Auth;

use Core\Database\AbstractDatabase;
use Core\Session\Session;

/**
 * Authentication class (works with MySQL only).
 *
 * @author <milos@caenazzo.com>
 */
class Auth
{
    /**
     * User table.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Connections variable to use work with database,
     * loaded in class constructor.
     *
     * @var object PDO
     */
    protected $conn = null;

    /**
     * PasswordHash object, used for password hashing.
     *
     * @var object
     */
    protected $hasher = null;

    /**
     * @var object Core\Session\Session
     */
    protected $session = null;

    /**
     * Class constructor.
     *
     * @param AbstractDatabase $db
     * @param Session $session
     * @param Hasher $hasher
     */
    public function __construct(AbstractDatabase $db = null, Session $session, $hasher)
    {
        // Set database connection link.
        $this->conn = $db->getConnection();

        // Set session link.
        $this->session = $session;

        // Set hasher tool
        $this->hasher = $hasher;
    }

    /**
     * Set table to work with.
     *
     * @param string
     * @return Auth
     */
    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Create user.
     *
     * @param string $username
     * @param string $password
     * @return bool|int
     */
    public function createUser($username, $password)
    {
        // Check if user exists
        $stmt = $this->conn->prepare(sprintf("SELECT user_name FROM %s WHERE user_name = :name", $this->table));
        $stmt->execute(['name' => $username]);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // If username already exists return false.
        if (count($result) !== 0) {
            return false;
        }

        // Hash password
        $password = $this->hasher->HashPassword($password);

        // Insert into database
        $stmt = $this->conn->prepare(sprintf("INSERT INTO %s (user_name, user_pass, user_date)
                                                              VALUES (:name, :pass, now())", $this->table));
        $stmt->execute(['name' => $username, 'pass' => $password]);

        // Return success status
        if ($stmt->rowCount() === 1) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    /**
     * Change user password.
     *
     * @param  string $username
     * @param  string $newPass
     * @return bool
     */
    public function changePassword($username, $newPass)
    {
        $password = $this->hasher->HashPassword($newPass);
        return $this
            ->conn
            ->prepare(sprintf("UPDATE %s SET user_pass=:newPass WHERE user_name = :username", $this->table))
            ->execute(['newPass' => $password, 'username' => $username]);
    }

    /**
     * Delete user.
     *
     * @param string $username
     * @return int
     */
    public function deleteUser($username)
    {
        $stmt = $this
            ->conn
            ->prepare(sprintf("DELETE * FROM %s WHERE user_name = :name", $this->table))
            ->execute(['name' => $username]);
        return $stmt->rowCount();
    }

    /**
     * Try to login user with passed parameters.
     *
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function login($username, $password)
    {
        $stmt = $this->conn->prepare(
            sprintf("SELECT user_id, user_name, user_pass
			FROM %s WHERE user_name = :name LIMIT 1", $this->table));
        $stmt->execute(['name' => $username]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($result['user_name'] !== $username) {
            return false;
        }

        if ($this->hasher->CheckPassword($password, $result['user_pass'])) {
            // Clear previous session
            $this->session->regenerate();
            // Write new data to session
            $data = [
                'id' => $result['user_id'],
                'logged_' . $this->table => true
            ];
            $this->session->set('user', $data);
            return true;
        }
        return false;
    }

    /**
     * Create users table.
     *
     * @param string $name
     * @return bool
     */
    public function createTable($name = null)
    {
        if ($name === null) {
            $name = $this->table;
        }
        $stmt = $this->conn->prepare("CREATE TABLE IF NOT EXISTS $name (
			  user_id int(10) unsigned NOT NULL auto_increment,
			  user_name varchar(255) NOT NULL default '',
			  user_pass varchar(60) NOT NULL default '',
			  user_date datetime NOT NULL default '0000-00-00 00:00:00',
			  user_modified datetime NOT NULL default '0000-00-00 00:00:00',
			  user_last_login datetime NULL default NULL,
			  PRIMARY KEY (user_id),
			  UNIQUE KEY user_name (user_name)
			) DEFAULT CHARSET=utf8");
        return $stmt->execute();
    }

    /**
     * Get id of current logged user, returns false if no user logged.
     *
     * @return int|null
     */
    public function getUserId()
    {
        if ($this->isLogged()) {
            return $this->session->get('user')['id'];
        } else {
            return null;
        }
    }

    /**
     * Check if there is logged user.
     *
     * @return bool
     */
    public function isLogged()
    {
        $user = $this->session->get('user');
        if (isset($user['logged_' . $this->table])
            && $user['logged_' . $this->table] === true
        ) {
            return true;
        }
        return false;
    }

    /**
     * Logout current user.
     */
    public function logout()
    {
        $this->session->forget('user');
    }
}