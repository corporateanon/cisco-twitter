<?php

namespace nanotwi\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Auth extends Base {

    public function loginForm(ServerRequestInterface $request, ResponseInterface $response) {
        return $this->view->render($response, 'login-form.twig');
    }

    public function login(ServerRequestInterface $request, ResponseInterface $response) {
        /* @var $flow nanotwi\OAuthFlow */
        $flow = $this->oAuthFlow;

        /* @var $router \Slim\Router */
        $router = $this->router;
        $base = $request->getUri()->getBaseUrl();
        return $flow->startAuthentication($response, $base . $router->urlFor('oauth-callback'));
    }

    public function oAuthCallback(ServerRequestInterface $request, ResponseInterface $response) {
        /* @var $flow nanotwi\OAuthFlow */
        $flow = $this->oAuthFlow;
        $flow->completeAuthentication($request);
        /* @var $router \Slim\Router */
        $router = $this->router;
        $base = $request->getUri()->getBaseUrl();
        return $response->withStatus(302)->withHeader('Location', $base . $router->urlFor('web'));
    }
}
