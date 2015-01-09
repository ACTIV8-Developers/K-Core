<?php 
namespace Core\Auth;

use Core\Session\Session;
use Core\Database\AbstractDatabase;

/**
* Authentication class (works with MySQL only).
*
* @author Milos Kajnaco <miloskajnaco@gmail.com>
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
	 * @var object \PDO
	 */
	protected $conn = null;

	/**
	 * PasswordHash object, used for password hashing.
	 *
	 * @var object
	 */
	protected $hasher = null;

	/**
	* @var object \Core\Session\Session
	*/
	protected $session = null;

	/**
	 * Class constructor.
	 *
     * @param object \Core\Database\AbstractDatabase
     * @param object \Core\Session\Session
	 */
	public function __construct(AbstractDatabase $db = null, Session $sess)
	{		
		// Set database connection link.
        $this->conn = $db->getConnection();

        // Set session link.
        $this->session = $sess;

		// Create hasher tool
		$this->hasher = new PasswordHash(8, FALSE);
	}

	/**
	 * Set table to work with.
	 *
	 * @param string 
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
		$stmt = $this->conn->prepare("SELECT user_name FROM $this->table WHERE user_name = :name");
		$stmt->execute([':name'=>$username]);
		$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		// If username already exists return false.
		if (count($result) !== 0) {
			return false;
		}

		// Hash password
		$password = $this->hasher->HashPassword($password);

		// Insert into database
		$stmt = $this->conn->prepare("INSERT INTO $this->table (user_name, user_pass, user_date) VALUES (:name, :pass, now())");
		$stmt->execute([':name'=>$username,':pass'=>$password]);

		// Return sucess status
		if ($stmt->rowCount() === 1) {
			return $this->conn->lastInsertId();;
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
        $stmt = $this->conn->prepare("UPDATE $this->table SET user_pass=:newPass WHERE user_name = :username");
        return $stmt->execute([':newPass'=>$password, ':username'=>$username]);
	}

	/**
	 * Delete user.
	 *
	 * @param string $username
	 * @return int
	 */
	public function deleteUser($username)
	{
        $stmt = $this->conn->prepare("DELETE * FROM $this->table WHERE user_name = :name");
        $stmt->execute([':name' => $username]);
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
		$stmt = $this->conn->prepare("SELECT user_id, user_name, user_pass 
			FROM $this->table WHERE user_name = :name LIMIT 1");
		$stmt->execute([':name'=>$username]);
		
		$result = $stmt->fetch(\PDO::FETCH_ASSOC);

		if ($result['user_name']!=$username) {
			return false;
		}

		if ($this->hasher->CheckPassword($password, $result['user_pass'])) {
			// Clear previous session
            $this->session->regenerate();
			// Write new data to session
            $_SESSION['user']['id'] = $result['user_id'];
			$_SESSION['user']['logged_'.$this->table] = true;
			return true;
		}
		return false;
	}

    /**
     * Create users table.
     *
     * @param string $name
     * @param array $additionalColumns
     * @return bool
     */
	public function createTable($name = null, array $additionalColumns = [])
	{
		if ($name === null) {
			$name = $this->table;
		}
		$stmt = $this->conn->prepare("CREATE TABLE $name (
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
     * @return int|bool
     */
    public function getUserId()
    {
        if ($this->isLogged()) {
            return $_SESSION['user']['id'];
        } else {
            return false;
        }
    }
        
	/**
	 * Check if there is logged user.
	 *
	 * @return bool
	 */
	public function isLogged()
	{
        if (isset($_SESSION['user']['logged_'.$this->table])
            && $_SESSION['user']['logged_'.$this->table] === true) {
        	return true;
        }
        return false;
	}

	/**
	 * Logout current user.
	 */
	public function logout()
	{
		session_unset();
		session_destroy();
	}
}