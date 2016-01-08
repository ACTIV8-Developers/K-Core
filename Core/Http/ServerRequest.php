<?php
namespace Core\Http;

/**
 * HTTP request class.
 *
 * This class provides processes common request parameters.
 *
 * @author <milos@caenazzo.com>
 */
class ServerRequest extends \Zend\Diactoros\ServerRequest
{

    /**
     * Server and execution environment parameters (parsed from $_SERVER).
     *
     * @var \Core\Http\HttpBag
     */
    public $server = null;

    /**
     * Request headers (parsed from the $_SERVER).
     *
     * @var \Core\Http\HttpBag
     * @see http://en.wikipedia.org/wiki/List_of_HTTP_header_fields
     */
    public $headers = null;

    /**
     * Request parameters (parsed from the $_GET).
     *
     * @var \Core\Http\HttpBag
     */
    public $get = null;

    /**
     * Request parameters (parsed from the $_POST).
     *
     * @var \Core\Http\HttpBag
     */
    public $post = null;

    /**
     * Request cookies (parsed from the $_COOKIE).
     *
     * @var \Core\Http\HttpBag
     */
    public $cookies = null;

    /**
     * Request files (parsed from the $_FILES).
     *
     * @var \Core\Http\HttpBag
     */
    public $files = null;

    /**
     * @param array $serverParams Server parameters, typically from $_SERVER
     * @param array $uploadedFiles Upload file information, a tree of UploadedFiles
     * @param null|string $uri URI for the request, if any.
     * @param null|string $method HTTP method for the request, if any.
     * @param string|resource|StreamInterface $body Message body, if any.
     * @param array $headers Headers for the message, if any.
     * @param array $cookies Cookies for the message, if any.
     * @param array $queryParams Query params for the message, if any.
     * @param null|array|object $parsedBody The deserialized body parameters, if any.
     * @param string HTTP protocol version.
     * @throws \InvalidArgumentException for any invalid value.
     */
    public function __construct(
        array $serverParams = [],
        array $uploadedFiles = [],
        $uri = null,
        $method = null,
        $body = 'php://input',
        array $headers = [],
        array $cookies = [],
        array $queryParams = [],
        $parsedBody = null,
        $protocol = '1.1'
    ) {
        parent::__construct($serverParams, $uploadedFiles, $uri, $method, $body, $headers, $cookies, $queryParams, $parsedBody, $protocol);
    }
}