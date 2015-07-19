<?php
namespace Core\Container;

use InvalidArgumentException;
use Core\Container\Interfaces\ContainerAwareInterface;

/**
 * Abstract class ContainerAware
 *
 * @author <milos@caenazzo.com>
 *
 * @property Core $app
 */
abstract class ContainerAware implements ContainerAwareInterface
{
    /**
     * @var Container $app
     */
    protected $app = null;

    /**
     * @param Container $app
     * @return self
     */
    public function setApp(Container $app)
    {
        $this->app = $app;
        return $this;
    }

    /**
     * @param $var
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function __get($var)
    {
        if (isset($this->app[$var])) {
            return $this->app[$var];
        } else {
            throw new InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $var));
        }
    }
}
