<?php
namespace Core\Routing;

use Core\Routing\Interfaces\ExecutableInterface;

/**
 * Executable class.
 * Class contains information about action to be executed.
 *
 * @author <milos@caenazzo.com>
 */
class Executable implements ExecutableInterface
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
     *
     * @return self
     */
    public function execute()
    {
        $this->class = $this->namespacePrefix . '\\' . $this->class;
        call_user_func_array([new $this->class, $this->method], $this->params);
        return $this;
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
     * @return self
     */
    public function setClass($class)
    {
        $this->class = $class;
        return $this;
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
     * @return self
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
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
     * @return self
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
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
     * @return self
     */
    public function setNamespacePrefix($namespacePrefix)
    {
        $this->namespacePrefix = $namespacePrefix;
        return $this;
    }
}