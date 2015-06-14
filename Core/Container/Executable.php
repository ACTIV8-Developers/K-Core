<?php
namespace Core\Container;

use Core\Container\Interfaces\ExecutableInterface;
use Core\Container\Interfaces\ContainerAwareInterface;

/**
 * Executable class.
 * Class contains information about action to be executed.
 *
 * @author <milos@caenazzo.com>
 */
class Executable implements ExecutableInterface, ContainerAwareInterface
{
    /**
     * @var Container
     */
    protected $app = null;

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
        // Add namespace prefix
        $this->class = '\\' . $this->class;

        // Create class
        $class = new $this->class();

        // If class needs container inject it
        if ($class instanceof ContainerAwareInterface) {
            $class->setApp($this->app);
        }

        // Execute class method
        call_user_func_array([$class, $this->method], $this->params);
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
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @return Container
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * @param Container $app
     * @return self
     */
    public function setApp(Container $app)
    {
        $this->app = $app;
        return $this;
    }
}