<?php
namespace Core\Core;

use Core\Container\Container;
use Core\Container\Interfaces\ContainerAwareInterface;
use Core\Routing\Interfaces\ResolverInterface;

/**
 * Class Resolver
 * @package Core\Core
 */
class Resolver implements ResolverInterface
{
    /**
     * @var Container|null
     */
    private $container = null;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Execute action
     *
     * @param string
     * @return object
     */
    public function resolve($classname)
    {
        // Resolve from container if possible
        if ($this->container->has($classname)) {
            return $this->container->get($classname);
        }

        // Add namespace prefix
        $class = '\\' . $classname;

        // Create class
        $object = new $class();

        // If class needs container inject it
        if ($object instanceof ContainerAwareInterface) {
            $object->setContainer($this->container);
        }
        return $object;
    }
}
