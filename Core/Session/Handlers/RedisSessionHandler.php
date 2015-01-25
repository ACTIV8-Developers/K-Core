<?php
namespace Core\Session\Handlers;

/**
 * Session handler using Redis mechanism.
 */
class RedisSessionHandler implements \SessionHandlerInterface
{
    /**
     * @var int
     */
    public $expire = 7200;

    /**
     * @var string
     */
    public $prefix = 'PHPSESSID:';

    /**
     * @var \Predis\Client
     */
    protected $redis;

    /**
     * @param \Predis\Client
     * @param string
     */
    public function __construct(\Predis\Client $redis) 
    {
        $this->redis = $redis;
    }
 
    public function open($savePath, $sessionName) 
    {
        // No action necessary because connection is injected
        // in constructor and arguments are not applicable.
    }
 
    public function close() 
    {
        $this->redis = null;
        unset($this->redis);
    }
 
    public function read($id) 
    {
        $id = $this->prefix.$id;
        $sessData = $this->redis->get($id);
        $this->redis->expire($id, $this->expire);
        return $sessData;
    }
 
    public function write($id, $data) 
    {
        $id = $this->prefix.$id;
        $this->redis->set($id, $data);
        $this->redis->expire($id, $this->expire);
    }
 
    public function destroy($id) 
    {
        $this->redis->del($this->prefix.$id);
    }
 
    public function gc($maxLifetime) 
    {
        // No action necessary because using expire Redis option.
    }
}