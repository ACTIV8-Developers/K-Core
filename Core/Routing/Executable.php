<?php
namespace Core\Routing;

use Core\Routing\Interfaces\ResolverInterface;

/**
 * Executable class.
 * Class contains information about action to be executed.
 *
 * @author <milos@activ8.rs>
 */
class Executable
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
     * @var null|ResolverInterface
     */
    protected $resolver = null;

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
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * @param array $params
     */
    public function addParams(array $params)
    {
        $this->params = array_merge($this->params, $params);
    }

    /**
     * @param ResolverInterface|null $resolver
     * @return self
     */
    public function setResolver(ResolverInterface $resolver)
    {
        $this->resolver = $resolver;
        return $this;
    }

    /**
     * Execute action
     * @return mixed
     */
    public function __invoke()
    {
        if ($this->resolver === null) {
            // Add namespace prefix
            $class = '\\' . $this->class;

            // Create class
            $object = new $class();
        } else {
            $object = $this->resolver->resolve($this->class);
        }

        // Execute class method
        return call_user_func_array([$object, $this->method], $this->params);
    }
}