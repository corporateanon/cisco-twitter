<?php

namespace nanotwi\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class NanoTwitter {

    /**
     * @var \Interop\Container\ContainerInterface
     */
    private $ci;

    /**
     * Execute the middleware.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next) {
        $oAuth = $request->getAttribute('oAuth');
        
        /* @var $nanoTwitter \nanotwi\NanoTwitter */
        $nanoTwitter = $this->ci->get('nanoTwitter');
        $nanoTwitter->setAuthentication($oAuth['oAuthToken'], $oAuth['oAuthTokenSecret']);
        return $next($request, $response);
    }

    public function __construct(\Interop\Container\ContainerInterface $ci) {
        $this->ci = $ci;
    }

}
