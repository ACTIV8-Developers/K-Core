<?php
namespace Core\Container;

use BadMethodCallException;
use InvalidArgumentException;

/**
 * Abstract class ContainerAware
 *
 * @author <milos@caenazzo.com>
 */
abstract class ContainerAware
{
    /**
     * @var Container $app
     */
    protected $app = null;

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

    /**
     * @param $var
     * @return Container|mixed
     * @throws InvalidArgumentException
     */
    public function __get($var)
    {
        if ($var === 'app') {
            return $this->app;
        } else {
            if (isset($this->app[$var])) {
                return $this->app[$var];
            } else {
                throw new InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $var));
            }
        }
    }

    /**
     * Handle calls to missing methods on the controller.
     *
     * @param  string $method
     * @param  array $parameters
     * @throws BadMethodCallException
     */
    public function __call($method, array $parameters)
    {
        throw new BadMethodCallException(sprintf('Method %s does not exist.', $method));
    }
}
