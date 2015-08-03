<?php
namespace Core\Routing;

use Core\Routing\Interfaces\ResolverInterface;
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
     * @param ResolverInterface|null $resolver
     * @return $this
     */
    public function execute(ResolverInterface $resolver = null)
    {
        if ($resolver === null) {
            // Add namespace prefix
            $class = '\\' . $this->class;

            // Create class
            $object = new $class();
        } else {
            $object = $resolver->resolve($this->class);
        }

        // Execute class method
        call_user_func_array([$object, $this->method], $this->params);
        return $this;
    }
}