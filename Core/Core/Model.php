<?php
namespace Core\Core;

use Core\Container\ContainerAware;
use Core\Http\Interfaces\RequestInterface;
use Core\Routing\Interfaces\RouterInterface;

/**
 * Base model abstract class.
 *
 * @author <milos@caenazzo.com>
 *
 * @property RequestInterface $request
 * @property RouterInterface $router
 * @property \ArrayAccess $config
 */
abstract class Model extends ContainerAware {}