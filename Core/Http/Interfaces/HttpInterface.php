<?php
namespace Core\Http\Interfaces;

/**
 * HttpInterface describes basic HTTP message attributes.
 *
 * @author <milos@caenazzocom>
 */
interface HttpInterface
{    
    /**
     * Get HTTP protocol version ("HTTP/1.1" or "HTTP/1.0").
     *
     * @return string
     */
    public function getProtocolVersion();

    /**
     * Set HTTP protocol version ("HTTP/1.1" or "HTTP/1.0").
     *
     * @param string $version
     * @return self
     */
    public function setProtocolVersion($version);

    /**
     * Returns an associative array of all current headers 
     *
     * Each key will be a header name with it's value
     *
     * @return array 
     */
    public function getHeaders();

    /**
     * Set new header, replacing any existing values 
     * of any headers with the same case-insensitive name
     *
     * @param string $key Case-insensitive header field name
     * @param string $value Header value
     * @return self
     */
    public function setHeader($key, $value);

    /**
     * Get header with passed key
     *
     * @param string $key Case-insensitive header field name
     * @return string
     */
    public function getHeader($key);


    /**
     * Get the body of the request
     *
     * @return string
     */
    public function getBody();

    /**
     * Set request body
     *
     * @param $body Body
     * @return self
     */
    public function setBody($body);
}