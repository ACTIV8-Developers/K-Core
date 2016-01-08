<?php
namespace Core\Core;

use Core\Container\ContainerAware;
use Core\Core\Exceptions\StopException;
use Core\Core\Exceptions\NotFoundException;
use Core\Http\Interfaces\RequestInterface;
use Core\Http\Interfaces\ResponseInterface;
use Core\Routing\Interfaces\RouterInterface;

/**
 * Base controller abstract class.
 * Extend to get access to container main container and common functions.
 *
 * @author <milos@caenazzo.com>
 *
 * @property RequestInterface $request
 * @property ResponseInterface $response
 * @property RouterInterface $router
 * @property \ArrayAccess $config
 */
abstract class Controller extends ContainerAware
{
    /**
     * Render output for display.
     *
     * @param string $view
     * @param array $data
     */
    protected function render($view, array $data = [])
    {
        // Extract variables.
        extract($data);

        // Start buffering.
        ob_start();

        // Load view file (root location is declared in viewPath var).
        include $this->container->get('config')['viewsPath'] . '/' . $view . '.php';

        // Add view to output body.
        $this->container->get('response')->getBody()->write(ob_get_contents());
        ob_end_clean();
    }

    /**
     * Buffer output and return it as string.
     *
     * @param string $view
     * @param array $data
     * @return string
     */
    protected function buffer($view, array $data = [])
    {
        // Extract variables.
        extract($data);

        // Start buffering.
        ob_start();

        // Load view file (root location is declared in viewPath var).
        include $this->container->get('config')['viewsPath'] . '/' . $view . '.php';

        // Return string.       
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }

    /**
     * Display page with not found code.
     *
     * @throws \Core\Core\Exceptions\NotFoundException
     */
    protected function notFound()
    {
        throw new NotFoundException();
    }

    /**
     * Stop controller execution and render current response
     *
     * @throws \Core\Core\Exceptions\StopException
     */
    protected function stop()
    {
        throw new StopException();
    }
}