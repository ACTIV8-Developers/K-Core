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
     * Get GET value from request object.
     *
     * @param string $key
     * @return mixed
     */
    protected function get($key = null)
    {
        if ($key === null) {
            return $this->container->get('request')->get->all();
        }
        return $this->container->get('request')->get->get($key);
    }

    /**
     * Get POST value from request object.
     *
     * @param string $key
     * @return mixed
     */
    protected function post($key = null)
    {
        if ($key === null) {
            return $this->container->get('request')->post->all();
        }
        return $this->container->get('request')->post->get($key);
    }

    /**
     * Get COOKIE value from request object.
     *
     * @param string $key
     * @return mixed
     */
    protected function cookies($key = null)
    {
        if ($key === null) {
            return $this->container->get('request')->cookies->all();
        }
        return $this->container->get('request')->cookies->get($key);
    }

    /**
     * Get FILES value from request object.
     *
     * @param string $key
     * @return mixed
     */
    protected function files($key = null)
    {
        if ($key === null) {
            return $this->container->get('request')->files->all();
        }
        return $this->container->get('request')->files->get($key);
    }

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
        $this->container->get('response')->addBody(ob_get_contents());
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
     * Set response type to JSON.
     *
     * @param array $data
     * @param int $options
     */
    public function json($data, $options = 0)
    {
        $this->container->get('response')->headers->set('Content-Type', 'containerlication/json');
        $this->container->get('response')->setBody(json_encode($data, $options));
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