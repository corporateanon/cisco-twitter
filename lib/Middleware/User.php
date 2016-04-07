<?php

namespace nanotwi\Middleware;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use \nanotwi\TwitterUser;
class User {

    const USER = 'nanotwi\\Middleware\\User';

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
        $user = null;
        if(isset($_SESSION) && isset($_SESSION['twitterUser'])) {
            $user = $_SESSION['twitterUser'];
        }
        return $next($request->withAttribute(self::USER, $user), $response);
    }
    
    public static function userFromRequest(ServerRequestInterface $request) {
        return new TwitterUser($request->getAttribute(self::USER));
    }

    
}
