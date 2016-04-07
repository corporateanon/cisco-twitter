<?php

namespace nanotwi\Middleware;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class WebAuthenticate {

    private $redirectToUrl;

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
        if(!$user) {
            $base = $request->getUri()->getBaseUrl();
            $response = $response->withStatus(302)->withHeader('Location', $base . $this->redirectToUrl);
        }
        return $next($request, $response);
    }
    
    public function redirectTo($url) {
        $this->redirectToUrl = $url;
        return $this;
    }

    
}
