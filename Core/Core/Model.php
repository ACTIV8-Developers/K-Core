<?php
namespace Core\Core;

use Core\Container\ContainerAware;

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
abstract class Model extends ContainerAware {}