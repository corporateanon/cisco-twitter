<?php

namespace nanotwi\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Services extends Base {

    public function timeline(ServerRequestInterface $request, ResponseInterface $response) {
        $tweets = $this->nanoTwitter->getTimeline();

        return $this->view
                        ->render($response, 'timeline.twig', [ 'statuses' => $tweets])
                        ->withHeader('Content-Type', 'text/xml');
    }

    public function tweet(ServerRequestInterface $request, ResponseInterface $response, $args) {
        $tweet = $this->nanoTwitter->getTweet($args['idStr']);

        return $this->view
                        ->render($response, 'tweet.twig', [ 'tweet' => $tweet])
                        ->withHeader('Content-Type', 'text/xml');
    }

    public function compose(ServerRequestInterface $request, ResponseInterface $response) {
        return $this->view
                        ->render($response, 'compose.twig', [])
                        ->withHeader('Content-Type', 'text/xml');
    }

    public function createTweet(ServerRequestInterface $request, ResponseInterface $response) {
        try {
            $params = $request->getQueryParams();
            $this->nanoTwitter->createTweet($params['statusText']);
            return $this->view
                            ->render($response, 'action-success.twig', [])
                            ->withHeader('Content-Type', 'text/xml');
        } catch (Exception $e) {
            return $this->view
                            ->render($response, 'action-error.twig', [ 'error' => $e])
                            ->withHeader('Content-Type', 'text/xml');
        }
    }

}
