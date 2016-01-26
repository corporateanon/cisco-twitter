<?php

use \nanotwi\Tweet;


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
