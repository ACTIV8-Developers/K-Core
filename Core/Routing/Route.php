<?php
namespace Core\Routing;

use Core\Routing\Interfaces\RouteInterface;
use Core\Routing\Interfaces\ExecutableInterface;

/**
 * Route class.
 * This class represents single application route.
 *
 * @author <milos@caenazzo.com>
 */
class Route implements RouteInterface
{
    /**
     * The route pattern (The URL pattern (e.g. "article/:year/:category")).
     *
     * @var string
     */
    protected $url = '';

    /**
     * List of supported HTTP methods for this route (GET, POST etc.).
     *
     * @var array
     */
    protected $methods = [];

    /**
     * @var array
     */
    protected $params = [];

    /**
     * Controller/method assigned to be executed when route is matched.
     *
     * @var ExecutableInterface
     */
    protected $executable = null;

    /**
     * @var null|ExecutableInterface
     */
    protected $before = null;

    /**
     * @var null|ExecutableInterface
     */
    protected $after = null;

    /**
     * List of parameters conditions.
     *
     * @var array
     */
    protected $conditions = [];

    /**
     * List of regex to use when matching conditions.
     *
     * @param array
     */
    protected static $conditionRegex = [
        'default' => '[a-zA-Z0-9_\-]+', // Default allows letters, numbers, underscores and dashes.
        'alpha-numeric' => '[a-zA-Z0-9]+', // Numbers and letters.
        'numeric' => '[0-9]+', // Numbers only.
        'alpha' => '[a-zA-Z]+', // Letters only.
        'alpha-lowercase' => '[a-z]+',  // All lowercase letter.
        'real-numeric' => '[0-9\.\-]+' // Numbers, dots or minus signs.
    ];

    /**
     * Regex used to parse routes.
     *
     * @var string
     */
    const MATCHES_REGEX = '@:([\w]+)@';

    /**
     * @param $url
     * @param $requestMethod
     * @param $class
     * @param $function
     */
    public function __construct($url, $requestMethod, $class, $function)
    {
        $this->url = $url;
        $this->methods[] = $requestMethod;
        $this->executable = ExecutableFactory::make($class, $function);
    }

    /**
     * Check if requested URI and method matches this route.
     * Inspired by: http://blog.sosedoff.com/2009/09/20/rails-like-php-url-router/
     *
     * @param string $uri
     * @param string $method
     * @return bool
     */
    public function matches($uri, $method)
    {
        // Check if request method matches.
        if (in_array($method, $this->methods)) {
            $paramValues = [];

            // Replace parameters with proper regex patterns.
            $urlRegex = preg_replace_callback(self::MATCHES_REGEX, [$this, 'regexUrl'], $this->url);

            // Check if URI matches and if it matches put results in values array.
            if (preg_match('@^' . $urlRegex . '/?$@', $uri, $paramValues) === 1) {// There is a match.
                // Extract parameter names.
                $paramNames = [];
                preg_match_all(self::MATCHES_REGEX, $this->url, $paramNames, PREG_PATTERN_ORDER);

                // Put parameters to array to be passed to controller/function later.
                foreach ($paramNames[0] as $index => $value) {
                    $this->params[substr($value, 1)] = urldecode($paramValues[$index + 1]);
                }
                // Everything is done return true.
                return true;
            }
        }
        // No match found return false.
        return false;
    }

    /**
     * Helper regex for matches function.
     *
     * @param string $matches
     * @return string
     */
    protected function regexUrl($matches)
    {
        $key = substr($matches[0], 1);
        if (isset($this->conditions[$key])) {
            return '(' . $this->conditions[$key] . ')';
        } else {
            return '(' . self::$conditionRegex['default'] . ')';
        }
    }

    /**
     * Set route parameter condition.
     *
     * @param string $key
     * @param string $condition
     * @return self
     */
    public function where($key, $condition)
    {
        $this->conditions[$key] = self::$conditionRegex[$condition];
        return $this;
    }

    /**
     * Set route custom parameter condition.
     *
     * @param string $key
     * @param string $pattern
     * @return self
     */
    public function whereRegex($key, $pattern)
    {
        $this->conditions[$key] = $pattern;
        return $this;
    }

    /**
     * Add GET as acceptable method.
     *
     * @return self
     */
    public function viaGet()
    {
        $this->methods[] = 'GET';
        return $this;
    }

    /**
     * Add POST as acceptable method.
     *
     * @return self
     */
    public function viaPost()
    {
        $this->methods[] = 'POST';
        return $this;
    }

    /**
     * Set supported HTTP method(s).
     *
     * @param array
     * @return self
     */
    public function setHttpMethods($methods)
    {
        $this->methods = $methods;
        return $this;
    }

    /**
     * Get supported HTTP method(s).
     *
     * @return array
     */
    public function getHttpMethods()
    {
        return $this->methods;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Get parameters associated passed with route if matched
     *
     * @return array
     */
    public function getExecutable()
    {
        return $this->executable;
    }

    /**
     * @param $function
     * @param $class
     */
    public function executeAfter($function, $class)
    {
        $this->after = ExecutableFactory::make($function, $class);
    }

    /**
     * @param $function
     * @param $class
     */
    public function executeBefore($function, $class)
    {
        $this->before = ExecutableFactory::make($function, $class);
    }
}