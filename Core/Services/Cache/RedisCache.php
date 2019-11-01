<?php

namespace Core\Services\Cache;

use DateInterval;
use DateTime;
use Exception;
use Psr\SimpleCache\CacheInterface;
use Redis;

/**
 * Class RedisCache
 */
class RedisCache implements CacheInterface
{
    const PSR16_RESERVED_CHARACTERS = ['{', '}', '(', ')', '/', '@', ':'];

    /**
     * @var Redis
     */
    private $handler;

    public function __construct($config)
    {
        try {
            $this->handler = new Redis();
            $this->handler->connect($config['host'], $config['port']);
        } catch (Exception $e) {

        }
    }

    public function getConnection()
    {
        return $this->handler;
    }

    /**
     * {@inheritDoc}
     */
    public function get($key, $default = null)
    {
        $value = $this->handler->get($key);
        return $value ? $value : $default;
    }

    /**
     * {@inheritDoc}
     */
    public function clear()
    {
        return $this->handler->flushDB();
    }

    /**
     * {@inheritDoc}
     */
    public function getMultiple($keys, $default = null)
    {
        $defaults = array_fill(0, count($keys), $default);
        foreach ($keys as $key) {
            $this->checkKeysValidity($key);
        }
        return array_merge(array_combine($keys, $this->handler->mget($keys)), $defaults);
    }

    private function checkKeysValidity($key)
    {
        if (!is_string($key)) {
            $message = sprintf('Key %s is not a string.', $key);
            throw new Exception($message);
        }
        foreach (self::PSR16_RESERVED_CHARACTERS as $needle) {
            if (strpos($key, $needle) !== false) {
                $message = sprintf('Key %s has not a legal value.', $key);
                throw new Exception($message);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setMultiple($values, $ttl = null)
    {
        foreach ($values as $key => $value) {
            $this->checkKeysValidity($key);
        }
        if ($ttl instanceof DateInterval) {
            $ttl = (new DateTime('now'))->add($ttl)->getTimeStamp() - time();
        }
        $setTtl = (int)$ttl;
        if ($setTtl === 0) {
            return $this->handler->mset($values);
        }
        $return = true;
        foreach ($values as $key => $value) {
            $return = $return && $this->set($key, $value, $setTtl);

        }
        return $return;
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value, $ttl = null)
    {
        $this->checkKeysValidity($key);
        if ($ttl instanceof DateInterval) {
            $ttl = (new DateTime('now'))->add($ttl)->getTimeStamp() - time();
        }
        $setTtl = (int)$ttl;
        if ($setTtl === 0) {
            return $this->handler->set($key, $value);
        }
        return $this->handler->setex($key, $ttl, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function deleteMultiple($keys)
    {
        foreach ($keys as $key) {
            $this->checkKeysValidity($key);
        }
        $return = [];
        foreach ($keys as $key) {
            $return[$key] = (bool)$this->delete($key);
        }
        return $return;
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        return (bool)$this->handler->delete($key);
    }

    /**
     * Increment a value atomically in the cache by its step value, which defaults to 1
     *
     * @param string $key The cache item key
     * @param integer $step The value to increment by, defaulting to 1
     *
     * @return int|bool The new value on success and false on failure
     */
    public function increment($key, $step = 1)
    {
        return $this->handler->incr($key, $step);
    }

    /**
     * Decrement a value atomically in the cache by its step value, which defaults to 1
     *
     * @param string $key The cache item key
     * @param integer $step The value to decrement by, defaulting to 1
     *
     * @return int|bool The new value on success and false on failure
     */
    public function decrement($key, $step = 1)
    {
        return $this->handler->decr($key, $step);
    }

    /**
     * {@inheritDoc}
     */
    public function has($key)
    {
        $this->checkKeysValidity($key);
        return $this->handler->exists($key);
    }
}