<?php

use \nanotwi\Tweet;


function getBaseUri($req) {
        $uri = $req->getUri();
        $port = $uri->getPort();
        if ($port == 443 || $port == 80 || !$port) {
                $port = '';
        } else {
                $port = ':'.$port;
        }
        return $uri->getScheme().'://'.$uri->getHost().$port;
}

$app->get('/', function ($req, $res, $args) {


        return $this->view
                ->render($res, 'index.xml', ['base' => getBaseUri($req)])
                ->withHeader('Content-Type', 'text/xml');
});

$app->get('/services/timeline', function ($req, $res, $args) {
        
        $tweets = $this->nanoTwitter->getTimeline();

        return $this->view
                ->render($res, 'timeline.xml', ['statuses' => $tweets, 'base' => getBaseUri($req) ] )
                ->withHeader('Content-Type', 'text/xml');
});

$app->get('/services/tweet/{idStr}', function ($req, $res, $args) {
        
        $tweet = $this->nanoTwitter->getTweet($args['idStr']);

        return $this->view
                ->render($res, 'tweet.xml', ['tweet' => $tweet, 'base' => getBaseUri($req) ] )
                ->withHeader('Content-Type', 'text/xml');
});
