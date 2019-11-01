<?php

namespace Core\Middleware;

use Core\Container\Container;
use Core\Container\ContainerAware;
use Core\Http\Response;
use OAuth2\Request;
use OAuth2\Server;

/**
 * Class AuthMiddleware
 * @property Server oauth
 * @property \Core\Http\Request request
 */
class AuthMiddleware extends ContainerAware
{
    /**
     * AuthMiddleware constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param callable $next
     * @return callable|Response
     */
    public function __invoke($next)
    {
        if (!$this->oauth->verifyResourceRequest(Request::createFromGlobals())) {
            $tokenResponse = $this->oauth->getResponse();
            $response = new Response();
            foreach ($tokenResponse->getHttpHeaders() as $name => $header) {
                $response->setHeader($name, $header);
            }
            $response->setHeader('Content-Type', 'application/json');
            return $response
                ->setStatusCode($tokenResponse->getStatusCode())
                ->setBody($tokenResponse->getResponseBody());
        }

        $accessTokenData = $this->oauth->getAccessTokenData(Request::createFromGlobals());

        // Get user data
        $this->container['user'] = $this->oauth->getStorage('client_credentials')->getUserDetails($accessTokenData['user_id']);

        // Get JSON request data
        $this->container['data'] = json_decode($this->request->getBody(), true);

        // Call next middleware
        return $next();
    }

}