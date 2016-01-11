<?php
namespace Core\Http\Interfaces;

/**
 * RequestInterface describes incoming server-side HTTP request.
 *
 * @author <milos@caenazzocom>
 */
interface RequestInterface extends HttpInterface
{
    /**
     * Retrieves the HTTP method of the request
     *
     * @return string
     */
    public function getMethod();

    /**
     * Set HTTP request method
     *
     * @param string
     * @return self
     */
    public function setMethod($method);

    /**
     * Retrieves the request URI
     *
     * @return string
     */
    public function getUri();

    /**
     * Sets the request URI
     *
     * @param string $uri New request URI to use
     * @return self
     */
    public function setUri($uri);
}