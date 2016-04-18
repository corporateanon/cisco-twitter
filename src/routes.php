<?php

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use nanotwi\Middleware\WebAuthenticate;
use nanotwi\Middleware\ServicesAuthenticate;
use nanotwi\Middleware\NanoTwitter as NanoTwitterMiddleware;

/* @var $app Slim\App */

$app->group('/', function () {
    $this->get('[/]', 'nanotwi\\Controller\\Web:autologinLink')->setName('web');
})->add((new WebAuthenticate())->redirectTo('/login'));


$app->group('/services/{token}', function () {
            $this->get('/timeline',      'nanotwi\\Controller\\Services:timeline')   ->setName('timeline');
            $this->get('/tweet/{idStr}', 'nanotwi\\Controller\\Services:tweet')      ->setName('tweet');
            $this->get('/compose',       'nanotwi\\Controller\\Services:compose')    ->setName('compose');
            $this->get('/createTweet',   'nanotwi\\Controller\\Services:createTweet')->setName('create_tweet');
        })
        ->add((new NanoTwitterMiddleware($app->getContainer())))
        ->add((new ServicesAuthenticate($app->getContainer())));


$app->get('/login', function (ServerRequestInterface $req, ResponseInterface $res) {
    /* @var $flow nanotwi\OAuthFlow */
    return $this->view->render($res, 'login-form.twig');
})->setName('login');


$app->post('/autologin-link', function (ServerRequestInterface $req, ResponseInterface $res) {
    /* @var $flow nanotwi\OAuthFlow */
    $flow = $this->oAuthFlow;

    /* @var $router \Slim\Router */
    $router = $this->router;
    $base = $req->getUri()->getBaseUrl();
    return $flow->startAuthentication($res, $base . $router->urlFor('oauth-callback'));
})->setName('autologin-link');


$app->get('/oauth-callback', function (ServerRequestInterface $req, ResponseInterface $res) {
    /* @var $flow nanotwi\OAuthFlow */
    $flow = $this->oAuthFlow;
    $flow->completeAuthentication($req);
    return $res->withStatus(302)->withHeader('Location', $req->getUri()->getBaseUrl() . '/web');
})->setName('oauth-callback');


