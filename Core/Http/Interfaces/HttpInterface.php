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
    public function getProtocolVersion(): string;

    /**
     * Set HTTP protocol version ("HTTP/1.1" or "HTTP/1.0").
     *
     * @param string $version
     * @return self
     */
    public function setProtocolVersion(string $version): HttpInterface;

    /**
     * Returns an associative array of all current headers
     *
     * Each key will be a header name with it's value
     *
     * @return array
     */
    public function getHeaders(): array;

    /**
     * Set new header, replacing any existing values
     * of any headers with the same case-insensitive name
     *
     * @param string $key Case-insensitive header field name
     * @param string $value Header value
     * @return self
     */
    public function setHeader(string $key, string $value): HttpInterface;

    /**
     * Get header with passed key
     *
     * @param string $key Case-insensitive header field name
     * @return string
     */
    public function getHeader(string $key): string;


    /**
     * Get the body of the request
     *
     * @return string
     */
    public function getBody(): string;

    /**
     * Set request body
     *
     * @param string $body
     * @return self
     */
    public function setBody(string $body): HttpInterface;
}