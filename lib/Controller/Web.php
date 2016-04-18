<?php

namespace nanotwi\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use nanotwi\Middleware\User;

class Web extends Base {

    public function autologinLink(ServerRequestInterface $request, ResponseInterface $response) {
        /* @var $autoLogin \nanotwi\AutoLogin */
        $autoLogin = $this->autoLogin;

        $user = User::getOauthUser($request);

        $hash = $autoLogin->createAutologinToken($user);

        return $this->view->render($response, 'web.twig', [ 'user' => $user, 'link' => $hash]);
    }

}
