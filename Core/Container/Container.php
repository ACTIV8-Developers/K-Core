<?php
namespace Core\Container;

use InvalidArgumentException;
use Interop\Container\ContainerInterface;
use Pimple\Container as PimpleContainer;

/**
 * Container
 *
 * @author <milos@caenazzo.com>
 */
class Container extends PimpleContainer implements ContainerInterface
{
    /**
     * Get entry from container
     *
     * @param string $key
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function get($key)
    {
        if (!$this->offsetExists($key)) {
            throw new InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $key));
        }
        return $this->offsetGet($key);
    }

    /**
     * Check for existence in container
     *
     * @param string $key
     * @return boolean
     */
    public function has($key)
    {
        return $this->offsetExists($key);
    }
}