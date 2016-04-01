<?php

use Psr\Http\Message\ServerRequestInterface;


$app->get('/', function ($req, $res, $args) {

  $services = [
    [ 'name' => 'Twitter', 'route' => 'timeline' ],
  ];

  return $this->view
    ->render($res, 'index.twig', [ 'services' => $services ])
    ->withHeader('Content-Type', 'text/xml');
});


$app->get('/services/timeline', function ($req, $res, $args) {

  $tweets = $this->nanoTwitter->getTimeline();

  return $this->view
    ->render($res, 'timeline.twig', [ 'statuses' => $tweets ] )
    ->withHeader('Content-Type', 'text/xml');
})->setName('timeline');


$app->get('/services/tweet/{idStr}', function ($req, $res, $args) {

  $tweet = $this->nanoTwitter->getTweet($args['idStr']);

  return $this->view
    ->render($res, 'tweet.twig', [ 'tweet' => $tweet ] )
    ->withHeader('Content-Type', 'text/xml');
})->setName('tweet');


$app->get('/services/compose', function ($req, $res, $args) {

  return $this->view
    ->render($res, 'compose.twig', [ ] )
    ->withHeader('Content-Type', 'text/xml');
})->setName('compose');


$app->get('/services/createTweet', function (ServerRequestInterface $req, $res) {

  try {
    $params = $req->getQueryParams();
    $this->nanoTwitter->createTweet($params['statusText']);
    return $this->view
      ->render($res, 'action-success.twig', [ ] )
      ->withHeader('Content-Type', 'text/xml');
  } catch (Exception $e) {
    return $this->view
      ->render($res, 'action-error.twig', [ 'error' => $e ] )
      ->withHeader('Content-Type', 'text/xml');
  }

})->setName('create_tweet');
