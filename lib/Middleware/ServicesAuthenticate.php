<?php

namespace nanotwi\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class ServicesAuthenticate {

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

        /* @var $view \Slim\Views\Twig */
        $view = $this->ci->get('view');

        /* @var $autoLogin \nanotwi\AutoLogin */
        $autoLogin = $this->ci->get('autoLogin');

        /* @var $autoLogin \nanotwi\NanoTwitter */
        $nanoTwitter = $this->ci->get('nanoTwitter');

        $routeInfo = $request->getAttribute('routeInfo')[2]; //https://github.com/slimphp/Slim/issues/1505#issuecomment-142193606
        $token = $routeInfo['token'];
        $oAuthToken = $autoLogin->parseAutologinToken($token);
        $view['token'] = $token;

        return $next($request
                        ->withAttribute('autologinToken', $token)
                        ->withAttribute('oAuth', $oAuthToken), $response);
    }

    public function __construct(\Interop\Container\ContainerInterface $ci) {
        $this->ci = $ci;
    }

}
