<?php

namespace nanotwi;

use Underscore\Types\Arrays;

class NanoTwitter {

    private $cache;
    private $consumerKey;
    private $consumerSecret;
    private $accessToken;
    private $accessTokenSecret;
    private $twitter;
    private $cachePrefix;

    function __construct($settings, $cache) {
        $this->cache = $cache;
        $this->consumerKey = $settings['consumerKey'];
        $this->consumerSecret = $settings['consumerSecret'];
    }

    public function setAuthentication($token, $secret) {
        $this->accessToken = $token;
        $this->accessTokenSecret = $secret;
        $this->cachePrefix = md5($token.$secret);
        $this->twitter = null;
    }
    
    private function getTwitter() {
        if(!$this->twitter) {
            if(!$this->accessToken || !$this->accessTokenSecret) {
                throw new Exception('Authentication required');
            }
            $this->twitter = new \Twitter($this->consumerKey, $this->consumerSecret, $this->accessToken, $this->accessTokenSecret);
        }
        return $this->twitter;
    }

    public function createTweet($text) {
        $this->getTwitter()->send($text);
        $this->cache->deleteAll(); //todo: does it work?
    }

    public function getTimeline() {
        $timelineKey = $this->cacheKey('timeline');
        if ($this->cache->contains($timelineKey)) {
            $statuses = $this->cache->fetch($timelineKey);
        } else {
            $statuses = $this->getTwitter()->request('statuses/home_timeline', 'GET', array('count' => 20));
            $this->cache->save($timelineKey, $statuses, 60);
        }

        $tweets = array_map(function ($status) {
            return new Tweet($status);
        }, $statuses);

        return $tweets;
    }

    public function getTweet($idStr) {
        $timelineKey = $this->cacheKey('timeline');
        if ($this->cache->contains($timelineKey)) {
            $statuses = $this->cache->fetch($timelineKey);

            $statusFoundInCache = Arrays::find($statuses, function ($status) use ($idStr) {
                        return $status->id_str === $idStr;
                    });

            if ($statusFoundInCache) {
                return new Tweet($statusFoundInCache);
            }
        }

        $status = $this->loadTweet($idStr);
        return new Tweet($status);
    }

    protected function loadTweet($idStr) {
        $tweetKey = $this->cacheKey('tweet.' . $idStr);

        if ($this->cache->contains($tweetKey)) {
            $status = $this->cache->fetch($tweetKey);
        } else {
            $status = $this->getTwitter()->request('statuses/show/' . $idStr, 'GET');
            $this->cache->save($tweetKey, $status, 3600);
        }

        return $status;
    }
    
    protected function cacheKey($key) {
        return $this->cachePrefix . '.' . $key;
    }

}
