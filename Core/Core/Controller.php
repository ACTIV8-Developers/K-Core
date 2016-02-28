<?php
namespace Core\Core;

use Core\Http\Response;
use Core\Container\ContainerAware;
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
     * @return ResponseInterface
     */
    protected function render($view, array $data = [])
    {
        // Extract variables.
        extract($data);

        // Start buffering.
        ob_start();

        // Load view file (root location is declared in viewPath var).
        include $this->container->get('config')['viewsPath'] . '/' . $view . '.php';

        // Get buffered content
        $body = ob_get_contents();
        ob_end_clean();

        // Make response and add body to it
        $response = new Response();
        $response->writeBody($body);
        return $response;
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

        // Load view file (root location is declared in viewsPath var).
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
}