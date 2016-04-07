<?php

use Psr7Middlewares\Middleware;
use nanotwi\Middleware\User;

/* @var $app Slim\App */

$app->add(new User());
$app->add(Middleware::PhpSession());

