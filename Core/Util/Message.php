<?php
namespace Core\Util;

/**
 * Class Message.
 *
 * Used for sending flash messages between requests.
 * (uses PHP session for storing data)
 *
 * @author milos@caenazzo.com
 */
class Message
{
    /**
     * Set message.
     *
     * @param string $key
     * @param mixed $value
     */
    public static function set($key, $value)
    {
        $_SESSION['flashmessage'][$key] = $value;
    }

    /**
     * Get message and destroy upon reading unless stated otherwise.
     *
     * @param string $key
     * @param bool $preserve
     * @return mixed
     */
    public static function get($key = '', $preserve = false)
    {
        $value = null;
        if (isset($_SESSION['flashmessage'][$key])) {
            $value = $_SESSION['flashmessage'][$key];
            if (!$preserve) {
                unset($_SESSION['flashmessage'][$key]);
            }
        }
        return $value;
    }
} 
