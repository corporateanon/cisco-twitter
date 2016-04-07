<?php

namespace nanotwi\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use nanotwi\Middleware\User;

class Web extends Base {

    public function autologinLink(ServerRequestInterface $request, ResponseInterface $response) {
        /* @var $flow nanotwi\OAuthFlow */
        $flow = $this->oAuthFlow;

        $user = User::userFromRequest($request);

        $hash = $flow->getAutologinLink($user);

        return $this->view->render($response, 'web.twig', [ 'user' => $user, 'link' => $hash]);
    }

}
