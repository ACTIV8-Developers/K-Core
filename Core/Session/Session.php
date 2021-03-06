<?php
namespace Core\Session;

/**
 * Session manager class.
 *
 * @author <milos@activ8.rs>
 */
class Session
{
    /**
     * Encryption key.
     *
     * @var string
     */
    protected $hashKey = 'super_secret';

    /**
     * Lifetime of the session cookie and session duration, defined in seconds.
     *
     * @var int
     */
    protected $expiration = 7200;

    /**
     * Cookie domain, for example 'www.php.net'.
     * To make cookies visible on all sub domains then the domain must be prefixed with a dot like '.php.net'.
     *
     * @var string|null
     */
    protected $domain = '';

    /**
     * If true the browser only sends the cookie over HTTPS.
     * Null denotes class will decide.
     *
     * @var bool|null
     */
    protected $secure = null;

    /**
     * Session name.
     *
     * @var string
     */
    protected $name = 'PHPSESS';

    /**
     * Match user agent across session requests.
     *
     * @var bool
     */
    protected $matchUserAgent = false;

    /**
     * Period of refreshing session ID.
     *
     * @var int
     */
    protected $updateFrequency = 10;

    /**
     * Hashing algorithm used for creating security tokens.
     *
     * @var string
     */
    protected $hashAlgo = 'md5';

    /**
     * Class construct.
     * Register handler and start session here.
     *
     * @param array $params
     * @param \SessionHandlerInterface
     */
    public function __construct(array $params = [], \SessionHandlerInterface $handler = null)
    {
        // Load configuration parameters.
        foreach ($params as $key => $val) {
            if (isset($this->$key)) {
                $this->$key = $val;
            }
        }

        // Set session cookie name.
        session_name($this->name);

        // Set the default secure value to whether the site is being accessed with SSL.
        $this->secure = $this->secure !== null ? $this->secure : isset($_SERVER['HTTPS']);

        // Set the domain to default or to the current domain.
        $this->domain = isset($this->domain) ? $this->domain : isset($_SERVER['SERVER_NAME']);

        // Set the cookie settings.
        session_set_cookie_params($this->expiration, '/', $this->domain, $this->secure, true);

        // Select session handler.
        if ($handler !== null) {
            // Assign session handler.
            session_set_save_handler($handler, true);
        }
    }

    /**
     * Start session.
     */
    public function start()
    {
        // If headers already sent can't do much
        if (headers_sent()) {
            return;
        }

        // If no active session start one.
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Validate session, if session is new or irregular clear data and start new session.
        if (!$this->validate()) {
            $this->regenerate();
        }

        // Regenerate session ID cycle.
        if ($this->updateFrequency !== 0 && mt_rand(1, 100) < $this->updateFrequency) {
            // Regenerate session
            session_regenerate_id();
        }
    }

    /**
     * Validate session.
     *
     * @return bool
     */
    protected function validate()
    {
        // Are needed session variables set ?
        if (!isset($_SESSION['s3ss10nCr3at3d']) || !isset($_SESSION['n3k0t'])) {
            return false;
        }

        // Check if session token match ?
        if ($this->matchUserAgent) {
            if ($_SESSION['n3k0t'] !== hash_hmac($this->hashAlgo, $_SERVER['HTTP_USER_AGENT'] . $_SESSION['s3ss10nCr3at3d'], $this->hashKey)) {
                return false;
            }
        } elseif ($_SESSION['n3k0t'] !== hash_hmac($this->hashAlgo, $_SESSION['s3ss10nCr3at3d'], $this->hashKey)) {
            return false;
        }

        // Is session expired ?
        if ((time() > ($_SESSION['s3ss10nCr3at3d']) + $this->expiration)) {
            return false;
        }

        // Everything is fine return true
        return true;
    }

    /**
     * Completely regenerate session.
     */
    public function regenerate()
    {
        // If headers already sent can't do much
        if (headers_sent()) {
            return;
        }
        
        // Clear old session data
        $_SESSION = [];
        // Set session start time
        $_SESSION['s3ss10nCr3at3d'] = time();
        // Set new session token
        if ($this->matchUserAgent) {
            $_SESSION['n3k0t'] = hash_hmac($this->hashAlgo, $_SERVER['HTTP_USER_AGENT'] . $_SESSION['s3ss10nCr3at3d'], $this->hashKey);
        } else {
            $_SESSION['n3k0t'] = hash_hmac($this->hashAlgo, $_SESSION['s3ss10nCr3at3d'], $this->hashKey);
        }
        // Regenerate session
        session_regenerate_id();
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @return array
     */
    public function all()
    {
        return $_SESSION;
    }

    /**
     * Clear session values.
     */
    public function clear()
    {
        unset($_SESSION);
        $_SESSION = [];
    }

    /**
     * @param string $key
     */
    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * @param string $hashKey
     */
    public function setHashKey($hashKey)
    {
        $this->hashKey = $hashKey;
    }
}