<?php
namespace Core\Http\Interfaces;

/**
 * ResponseInterface describes outgoing HTTP response
 *
 * @author <milos@activ8.rs>
 */
interface ResponseInterface extends HttpInterface
{
    /**
     * Get the response Status-Code
     *
     * @return integer Status code
     */
    public function getStatusCode();

    /**
     * Set the response Status-Code
     *
     * @param integer $code The 3-digit integer result code to set.
     * @param null|string $reasonPhrase The reason phrase to use with the
     *     provided status code; if none is provided default one associated with code will be used
     * @return self
     */
    public function setStatusCode($code, $reasonPhrase = null);

    /**
     * Send response back to browser
     */
    public function send();
}