<?php

namespace nanotwi\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class ProxiedHttpsSupport {

    const PROTO_HEADER_HEROKU = 'x-forwarded-proto';
    const PORT_HEADER_HEROKU = 'x-forwarded-port';

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
        return $next($request->withUri(self::getRealUri($request), true), $response);
    }
    
    public static function getRealUri(ServerRequestInterface $request) {
        $uri = $request->getUri();

        if($request->hasHeader(self::PROTO_HEADER_HEROKU)) {
            $uri = $uri->withScheme($request->getHeader(self::PROTO_HEADER_HEROKU)[0]);
        }
        if($request->hasHeader(self::PORT_HEADER_HEROKU)) {
            $uri = $uri->withScheme(intval($request->getHeader(self::PORT_HEADER_HEROKU)[0]));
        }
        return $uri;
    }
}
