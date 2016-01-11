<?php
namespace Core\Http;

use Core\Http\Interfaces\ResponseInterface;

/**
 * HTTP response class.
 *
 * This class provides simple abstraction over top an HTTP response.
 * This class provides methods to set the HTTP status, the HTTP headers,
 * the HTTP cookies and the HTTP body.
 *
 * @author <milos@caenazzo.com>
 */
class Response implements ResponseInterface
{
    /**
     * HTTP response codes and messages.
     *
     * @var array
     * @see http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
     */
    protected static $messages = [
        // Informational 1xx.
        100 => '100 Continue',
        101 => '101 Switching Protocols',
        // Successful 2xx.
        200 => '200 OK',
        201 => '201 Created',
        202 => '202 Accepted',
        203 => '203 Non-Authoritative Information',
        204 => '204 No Content',
        205 => '205 Reset Content',
        206 => '206 Partial Content',
        // Redirection 3xx.
        300 => '300 Multiple Choices',
        301 => '301 Moved Permanently',
        302 => '302 Found',
        303 => '303 See Other',
        304 => '304 Not Modified',
        305 => '305 Use Proxy',
        306 => '306 (Unused)',
        307 => '307 Temporary Redirect',
        // Client Error 4xx.
        400 => '400 Bad Request',
        401 => '401 Unauthorized',
        402 => '402 Payment Required',
        403 => '403 Forbidden',
        404 => '404 Not Found',
        405 => '405 Method Not Allowed',
        406 => '406 Not Acceptable',
        407 => '407 Proxy Authentication Required',
        408 => '408 Request Timeout',
        409 => '409 Conflict',
        410 => '410 Gone',
        411 => '411 Length Required',
        412 => '412 Precondition Failed',
        413 => '413 Request Entity Too Large',
        414 => '414 Request-URI Too Long',
        415 => '415 Unsupported Media Type',
        416 => '416 Requested Range Not Satisfiable',
        417 => '417 Expectation Failed',
        418 => '418 I\'m a teapot',
        422 => '422 Unprocessable Entity',
        423 => '423 Locked',
        // Server Error 5xx.
        500 => '500 Internal Server Error',
        501 => '501 Not Implemented',
        502 => '502 Bad Gateway',
        503 => '503 Service Unavailable',
        504 => '504 Gateway Timeout',
        505 => '505 HTTP Version Not Supported'
    ];

    /**
     * List of HTTP headers to be sent.
     *
     * @var \Core\Http\HttpBag
     */
    public $headers = null;
    /**
     * Array of cookies to be sent.
     *
     * @var \Core\Http\HttpBag
     */
    public $cookies = null;
    /**
     * HTTP response protocol version.
     *
     * @var string
     */
    protected $protocolVersion = 'HTTP/1.1';
    /**
     * HTTP response code.
     *
     * @var int
     */
    protected $statusCode = 200;
    /**
     * HTTP reason phrase.
     *
     * @var string|null
     */
    protected $reasonPhrase = null;
    /**
     * HTTP response body.
     *
     * @var string
     */
    protected $body = '';

    /**
     * Class construct
     */
    public function __construct()
    {
        $this->headers = new HttpBag();
        $this->cookies = new HttpBag();
    }

    /**
     * Append to HTTP response body.
     *
     * @param string $part
     * @return self
     */
    public function writeBody($part)
    {
        $this->body .= $part;
        return $this;
    }

    /**
     * Get the body of the request
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set request body
     *
     * @param string $body
     * @return self
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Get the response Status-Code
     *
     * @return integer Status code
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set the response Status-Code
     *
     * @param integer $code The 3-digit integer result code to set.
     * @param null|string $reasonPhrase The reason phrase to use with the
     *     provided status code; if none is provided default one associated with code will be used
     * @return self
     */
    public function setStatusCode($code, $reasonPhrase = null)
    {
        $this->statusCode = $code;
        $this->reasonPhrase = $reasonPhrase;
        return $this;
    }

    /**
     * Get HTTP protocol version ("HTTP/1.1" or "HTTP/1.0").
     *
     * @return string
     */
    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    /**
     * Set HTTP protocol version ("HTTP/1.1" or "HTTP/1.0").
     *
     * @param string $version
     * @return self
     */
    public function setProtocolVersion($version)
    {
        $this->protocolVersion = $version;
        return $this;
    }

    /**
     * Returns an associative array of all current headers
     *
     * Each key will be a header name with it's value
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers->all();
    }

    /**
     * Set new header, replacing any existing values
     * of any headers with the same case-insensitive name
     *
     * @param string $key Case-insensitive header field name
     * @param string $value Header value
     * @return self
     */
    public function setHeader($key, $value)
    {
        $this->headers->set($key, $value);
        return $this;
    }

    /**
     * Get header with passed key
     *
     * @param string $key Case-insensitive header field name
     * @return string
     */
    public function getHeader($key)
    {
        return $this->headers->get($key);
    }

    /**
     * Send cookie with response.
     *
     * @param string $name
     * @param string $value
     * @param int|string|\DateTime $expire
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httpOnly
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setCookie($name, $value = null, $expire = 7200, $path = '/', $domain = null, $secure = false, $httpOnly = true)
    {
        $this->cookies->set($name, new Cookie($name, $value, $expire, $path, $domain, $secure, $httpOnly));
        return $this;
    }

    /**
     * Send final status, headers, cookies and body.
     *
     * @return self
     */
    public function send()
    {
        // Check if headers are sent already.
        if (headers_sent() === false) {

            // Determine reason phrase
            if ($this->reasonPhrase === null) {
                $this->reasonPhrase = self::$messages[$this->statusCode];
            }

            // Send status code.
            header(sprintf('%s %s', $this->protocolVersion, $this->reasonPhrase), true, $this->statusCode);

            // Send headers.
            foreach ($this->headers as $header => $value) {
                header(sprintf('%s: %s', $header, $value), true, $this->statusCode);
            }

            // Send cookies.
            foreach ($this->cookies as $cookie) {
                setcookie($cookie['name'], $cookie['value'], $cookie['expire'],
                    $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httponly']);
            }

            // Send body.
            echo $this->body;
        }

        return $this;
    }
}