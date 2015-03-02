<?php
namespace Core\Routing;

/**
 * Action class.
 * Class contains information about action to be executed when route is matched.
 *
 * @author <milos@caenazzo.com>
 */
class Action
{
    /**
     * @var string
     */
    protected $class = null;

    /**
     * @var string
     */
    protected $method = null;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var string
     */
    protected $namespacePrefix = '';

    /**
     * Class constructor.
     *
     * @param string $class
     * @param string $method
     * @param array $params
     */
    public function __construct($class, $method, array $params = [])
    {
        $this->class = $class;
        $this->method = $method;
        $this->params = $params;
    }

    /**
     * Execute action
     */
    public function execute()
    {
        $this->class = $this->namespacePrefix.'\\'.$this->class;
        call_user_func_array([new $this->class, $this->method], $this->params);
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getNamespacePrefix()
    {
        return $this->namespacePrefix;
    }

    /**
     * @param string $namespacePrefix
     */
    public function setNamespacePrefix($namespacePrefix)
    {
        $this->namespacePrefix = $namespacePrefix;
    }
}