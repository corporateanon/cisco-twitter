<?php

use nanotwi\Middleware\WebAuthenticate;
use nanotwi\Middleware\ServicesAuthenticate;
use nanotwi\Middleware\NanoTwitter as NanoTwitterMiddleware;

/* @var $app Slim\App */

$app->get('/', 'nanotwi\\Controller\\Web:autologinLink')->setName('web')
        ->add((new WebAuthenticate($app->getContainer()))->redirectTo('loginForm'));

$app->get('/l',              'nanotwi\\Controller\\Auth:loginForm')    ->setName('loginForm');
$app->post('/login',         'nanotwi\\Controller\\Auth:login')        ->setName('login');
$app->get('/oauth-callback', 'nanotwi\\Controller\\Auth:oAuthCallback')->setName('oauth-callback');

$app->group('/services/{token}', function () {
            $this->get('/timeline',      'nanotwi\\Controller\\Services:timeline')   ->setName('timeline');
            $this->get('/tweet/{idStr}', 'nanotwi\\Controller\\Services:tweet')      ->setName('tweet');
            $this->get('/compose',       'nanotwi\\Controller\\Services:compose')    ->setName('compose');
            $this->get('/createTweet',   'nanotwi\\Controller\\Services:createTweet')->setName('create_tweet');
        })
        ->add(new NanoTwitterMiddleware($app->getContainer()))
        ->add(new ServicesAuthenticate($app->getContainer()));

