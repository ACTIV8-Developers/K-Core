<?php
namespace Core\Core;

use Core\Container\ContainerProvider;
use Core\Core\Exceptions\StopException;
use Core\Core\Exceptions\NotFoundException;

/**
 * Base controller abstract class.
 * Extend to get access to app main container and common functions.
 *
 * @author <milos@caenazzo.com>
 */
abstract class Controller extends ContainerProvider
{

    /**
     * @var string
     */
    public static $viewPath = '';

    /**
     * Get GET value from request object.
     *
     * @param string $key
     * @return mixed
     */
    protected function get($key = null)
    {
        if ($key === null) {
            return $this->app['request']->get->all();
        }
        return $this->app['request']->get->get($key);
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
            return $this->app['request']->post->all();
        }
        return $this->app['request']->post->get($key);
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
        include self::$viewPath.$view.'.php';

        // containerend to output body.
        $this->app['response']->addBody(ob_get_contents());
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
        include self::$viewPath.$view.'.php';

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
        $this->app['response']->headers->set('Content-Type', 'containerlication/json');
        $this->app['response']->setBody(json_encode($data, $options));
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