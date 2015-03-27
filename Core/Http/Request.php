<?php
namespace Core\Http;

use Core\Http\Interfaces\RequestInterface;

/**
 * HTTP request class.
 *
 * This class provides processes common request parameters.
 *
 * @author <milos@caenazzo.com>
 */
class Request implements RequestInterface
{
    /**
     * List of possible HTTP request methods.
     */
    const METHOD_HEAD = 'HEAD';
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';
    const METHOD_OPTIONS = 'OPTIONS';

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
     * Raw request body.
     *
     * @var string
     */
    protected $body = '';

    /**
     * Class constructor.
     *
     * @param array $server
     * @param array $get
     * @param array $post
     * @param array $cookies
     * @param array $files
     * @throws \InvalidArgumentException
     */
    public function __construct(array $server = [], array $get = [], array $post = [], array $cookies = [], array $files = [])
    {
        // Check if request is valid, need URI and method set at least.
        if (!isset($server['REQUEST_URI']) || !isset($server['REQUEST_METHOD'])) {
            throw new \InvalidArgumentException('HTTP request must have URI and method set.');
        }
        
        // Fix URI if needed.
        if (strpos($server['REQUEST_URI'], $server['SCRIPT_NAME']) === 0) {
            $server['REQUEST_URI'] = substr($server['REQUEST_URI'], strlen($server['SCRIPT_NAME']));
        } elseif (strpos($server['REQUEST_URI'], dirname($server['SCRIPT_NAME'])) === 0) {
            $server['REQUEST_URI'] = substr($server['REQUEST_URI'], strlen(dirname($server['SCRIPT_NAME'])));
        }
        if(!empty($_SERVER['QUERY_STRING'])) {
            $server['REQUEST_URI'] = str_replace('?'.$_SERVER['QUERY_STRING'], '', $server['REQUEST_URI']);
        }
        $server['REQUEST_URI'] = trim($server['REQUEST_URI'], '/');

        // Parse request headers and environment variables.
        $this->headers = new HttpBag();
        $this->server = new HttpBag();

        $specialHeaders = ['CONTENT_TYPE', 'CONTENT_LENGTH', 'PHP_AUTH_USER', 'PHP_AUTH_PW', 'PHP_AUTH_DIGEST', 'AUTH_TYPE'];
        foreach ($server as $key => $value) {
            $key = strtoupper($key);
            if (strpos($key, 'HTTP_') === 0 || in_array($key, $specialHeaders)) {
                if ($key === 'HTTP_CONTENT_TYPE' || $key === 'HTTP_CONTENT_LENGTH') {
                    continue;
                }
                $this->headers->set($key, $value);
            } else {
                $this->server->set($key, $value);
            }
        }

        // Since PHP doesn't support PUT, DELETE, PATCH naturally for these methods we will parse data directly from source.
        if (0 === strpos($this->headers->get('CONTENT_TYPE'), 'application/x-www-form-urlencoded')
            && in_array($this->server->get('REQUEST_METHOD'), array('PUT', 'DELETE', 'PATCH'))) {
            parse_str($this->getContent(), $data);
            $this->post = new HttpBag($data);
        } else {
            $this->post = new HttpBag($post); 
        }
        
        // Set GET parameters, cookies and files.
        $this->get = new HttpBag($get);
        $this->cookies = new HttpBag($cookies); 
        $this->files = new HttpBag($files); 
    }

    /**
     * Get the body of the request
     *
     * @return string
     */
    public function getBody()
    {
        if (null === $this->body) {
            $this->body = file_get_contents('php://input');
        }
        return $this->body;
    }

    /**
     * Set request body
     *
     * @param $body Body
     * @return self
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Retrieves the request URI
     *
     * @return string
     */
    public function getUri()
    {
        return $this->server->get('REQUEST_URI');
    }

    /**
     * Sets the request URI
     *
     * @param string $uri New request URI to use
     * @return self
     */
    public function setUri($uri)
    {
        $this->server->set('REQUEST_URI', $uri);
        return $this;
    }

    /**
     * Get request URI segment.
     *
     * @param int $index
     * @return string|bool
     */
    public function getUriSegment($index)
    {
        $segments = explode('/', $this->server->get('REQUEST_URI'));
        if (isset($segments[$index])) {
            return $segments[$index];
        }
        return false;
    }

    /**
     * Get HTTP protocol version ("HTTP/1.1" or "HTTP/1.0").
     *
     * @return string
     */
    public function getProtocolVersion()
    {
        return $this->server->get('SERVER_PROTOCOL');
    }

    /**
     * Set HTTP protocol version ("HTTP/1.1" or "HTTP/1.0").
     *
     * @param string $version
     * @return self
     */
    public function setProtocolVersion($version)
    {
        $this->server->set('SERVER_PROTOCOL', $version);
        return $this;
    }

    /**
     * Check if it is AJAX request.
     *  
     * @return bool
     */
    public function isAjax()
    {
        if ($this->headers->get('HTTP_X_REQUESTED_WITH') !== null
            && strtolower($this->headers->get('HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest') {
            return true;
        }
        return false;
    }

    /**
     * Retrieves the HTTP method of the request
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->server->get('REQUEST_METHOD');
    }

    /**
     * Set HTTP request method
     *
     * @return string
     * @return self
     */
    public function setMethod($method)
    {
        $this->server->set('REQUEST_METHOD', $method);
        return $this;
    }

    /**
     * Check if it is HEAD request.
     *
     * @return bool
     */
    public function isHead()
    {
        return self::METHOD_HEAD === $this->server->get('REQUEST_METHOD');
    }

    /**
     * Check if it is GET request.
     *
     * @return bool
     */
    public function isGet()
    {
        return self::METHOD_GET === $this->server->get('REQUEST_METHOD');
    }

    /**
     * Check if it is POST request.  
     *
     * @return bool
     */
    public function isPost()
    {
        return self::METHOD_POST === $this->server->get('REQUEST_METHOD');
    }

    /**
     * Check if it is PUT request.
     *  
     * @return bool
     */
    public function isPut()
    {
        return self::METHOD_PUT === $this->server->get('REQUEST_METHOD');
    }

    /**
     * Check if it is PATCH request. 
     * 
     * @return bool
     */
    public function isPatch()
    {
        return self::METHOD_PATCH === $this->server->get('REQUEST_METHOD');
    }

    /**
     * Check if it is DELETE request.  
     *
     * @return bool
     */
    public function isDelete()
    {
        return self::METHOD_DELETE === $this->server->get('REQUEST_METHOD');
    }

    /**
     * Check if it is OPTIONS request. 
     * 
     * @return bool
     */
    public function isOptions()
    {
        return self::METHOD_OPTIONS === $this->server->get('REQUEST_METHOD');
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
     * Get user agent.
     *
     * @return string|null
     */
    public function getUserAgent()
    {
        return $this->headers->get('HTTP_USER_AGENT');
    }

    /**
     * Get HTTP referrer.
     *
     * @return string|null
     */
    public function getReferer()
    {
        return $this->headers->get('HTTP_REFERER');
    }

    /**
     * Get content type.
     *
     * @return string|null
     */
    public function getContentType()
    {
        return $this->headers->get('CONTENT_TYPE');
    }

    /**
     * Get content length.
     *
     * @return string|null
     */
    public function getContentLength()
    {
        return $this->headers->get('CONTENT_LENGTH');
    }
}