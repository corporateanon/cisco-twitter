<?php

namespace nanotwi\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class WebAuthenticate {

    private $redirectToRouteName;

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
        $user = $request->getAttribute(User::USER);
        if (!$user) {
            /* @var $router \Slim\Router */
            $router = $this->ci->get('router');
            $response = $response->withStatus(302)->withHeader('Location', $router->urlFor($this->redirectToRouteName));
        }
        return $next($request, $response);
    }

    public function redirectTo($routeName) {
        $this->redirectToRouteName = $routeName;
        return $this;
    }

    public function __construct(\Interop\Container\ContainerInterface $ci) {
        $this->ci = $ci;
    }

}
