<?php

use \nanotwi\Tweet;


function getBaseUri($req) {
        $uri = $req->getUri();
        return $uri->getScheme().'://'.$uri->getHost();
}

$app->get('/', function ($req, $res, $args) {


        return $this->view
                ->render($res, 'index.xml', ['base' => getBaseUri($req)])
                ->withHeader('Content-Type', 'text/xml');
});

$app->get('/services/test', function ($req, $res, $args) {

        return $this->view
                ->render($res, 'test.xml', ['base' => getBaseUri($req)])
                ->withHeader('Content-Type', 'text/xml');
});

$app->get('/services/timeline', function ($req, $res, $args) {
        

        if ($this->cache->contains('timeline')) {
                $statuses = $this->cache->fetch('timeline');
        } else {
                $statuses = $this->twitter->request('statuses/home_timeline', 'GET', array('count' => 20));
                $this->cache->save('timeline', $statuses, 60);
        }

        $tweets = array_map(function ($status) {
                return new Tweet($status);
        }, $statuses);
    
        return $this->view
                ->render($res, 'timeline.xml', ['statuses' => $tweets, 'base' => getBaseUri($req) ] )
                ->withHeader('Content-Type', 'text/xml');
});


// $app->get('/', function ($req, $res, $args) {
        

//         if ($this->cache->contains('timeline')) {
//                 $statuses = $this->cache->fetch('timeline');
//         } else {
//                 $statuses = $this->twitter->request('statuses/home_timeline', 'GET', array('count' => 100));
//                 $this->cache->save('timeline', $statuses, 60);
//         }

//         $tweets = array_map(function ($status) {
//                 return new Tweet($status);
//         }, $statuses);
    
//         return $this->view->render($res, 'timeline.html', array('statuses' => $tweets));
// });

// $app->post('/timeline-force-refresh', function ($req, $res, $args) {
//         $this->cache->delete('timeline');
//         return $res
//                 ->withStatus(301)
//                 ->withHeader('Location', '/');
// });

