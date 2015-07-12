<?php
namespace Core\Core;

use InvalidArgumentException;
use Core\Http\Interfaces\RequestInterface;
use Core\Http\Interfaces\ResponseInterface;
use Core\Routing\Interfaces\RouterInterface;
use Core\Container\Container;
use Core\Container\Interfaces\ContainerAwareInterface;

/**
 * Base model abstract class.
 *
 * @author <milos@caenazzo.com>
 *
 * @property RequestInterface $request
 * @property ResponseInterface $response
 * @property RouterInterface $router
 * @property \ArrayAccess $config
 */
abstract class Model implements ContainerAwareInterface
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