<?php

use Psr7Middlewares\Middleware;
use nanotwi\Middleware\User;
use nanotwi\Middleware\ProxiedHttpsSupport;

/* @var $app Slim\App */

$app->add(new User());
$app->add(Middleware::PhpSession());
$app->add(new ProxiedHttpsSupport());
