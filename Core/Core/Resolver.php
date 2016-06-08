<?php
namespace Core\Core;

use Interop\Container\ContainerInterface;
use Core\Routing\Interfaces\ResolverInterface;
use Core\Container\Interfaces\ContainerAwareInterface;

/**
 * Class Resolver
 * @package Core\Core
 */
class Resolver implements ResolverInterface
{
    /**
     * @var ContainerInterface|null
     */
    private $container = null;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
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
