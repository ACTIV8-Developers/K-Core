<?php
namespace Core\Core;

use Core\Container\Container;
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
    private $app = null;

    /**
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
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
        if ($this->app->has($classname)) {
            return $this->app->get($classname);
        }

        // Add namespace prefix
        $class = '\\' . $classname;

        // Create class
        $object = new $class();

        // If class needs container inject it
        if ($object instanceof ContainerAwareInterface) {
            $object->setApp($this->app);
        }
        return $object;
    }
}
