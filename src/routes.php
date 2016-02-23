<?php

use \nanotwi\Tweet;
use Psr\Http\Message\ServerRequestInterface;


$app->get('/', function ($req, $res, $args) {

  $services = [
    [ 'name' => 'Twitter', 'route' => 'timeline' ],
  ];

  return $this->view
    ->render($res, 'index.xml', [ 'services' => $services ])
    ->withHeader('Content-Type', 'text/xml');
});


$app->get('/services/timeline', function ($req, $res, $args) {

  $tweets = $this->nanoTwitter->getTimeline();

  return $this->view
    ->render($res, 'timeline.xml', [ 'statuses' => $tweets ] )
    ->withHeader('Content-Type', 'text/xml');
})->setName('timeline');


$app->get('/services/tweet/{idStr}', function ($req, $res, $args) {

  $tweet = $this->nanoTwitter->getTweet($args['idStr']);

  return $this->view
    ->render($res, 'tweet.xml', [ 'tweet' => $tweet ] )
    ->withHeader('Content-Type', 'text/xml');
})->setName('tweet');


$app->get('/services/compose', function ($req, $res, $args) {

  return $this->view
    ->render($res, 'compose.xml', [ ] )
    ->withHeader('Content-Type', 'text/xml');
})->setName('compose');


$app->get('/services/createTweet', function (ServerRequestInterface $req, $res) {

  try {
    $params = $req->getQueryParams();
    $this->nanoTwitter->createTweet($params['statusText']);
    return $this->view
      ->render($res, 'compose-success.xml', [ ] )
      ->withHeader('Content-Type', 'text/xml');
  } catch (Exception $e) {
    return $this->view
      ->render($res, 'compose-error.xml', [ 'error' => $e ] )
      ->withHeader('Content-Type', 'text/xml');
  }

})->setName('create_tweet');
