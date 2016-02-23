<?php

namespace nanotwi;

use Underscore\Types\Arrays;

class NanoTwitter {
        function __construct($twitter, $cache) {
                $this->twitter = $twitter;
                $this->cache = $cache;
        }

        public function createTweet($text) {
                $this->twitter->send($text);
                $this->cache->deleteAll(); //todo: does it work?
        }

        public function getTimeline() {
                if ($this->cache->contains('timeline')) {
                        $statuses = $this->cache->fetch('timeline');
                } else {
                        $statuses = $this->twitter->request('statuses/home_timeline', 'GET', array('count' => 20));
                        $this->cache->save('timeline', $statuses, 60);
                }

                $tweets = array_map(function ($status) {
                        return new Tweet($status);
                }, $statuses);

                return $tweets;
        }

        public function getTweet($idStr) {
                if ($this->cache->contains('timeline')) {
                        $statuses = $this->cache->fetch('timeline');

                        $statusFoundInCache = Arrays::find($statuses, function ($status) use ($idStr) {
                                return $status->id_str === $idStr;
                        });

                        if($statusFoundInCache) {
                                return new Tweet($statusFoundInCache);
                        }
                }

                $status = $this->loadTweet($idStr);
                return new Tweet($status);
        }

        protected function loadTweet($idStr) {
                $key = 'tweet_'.$idStr;

                if ($this->cache->contains($key)) {
                        $status = $this->cache->fetch($key);
                } else {
                        $status = $this->twitter->request('statuses/show/'.$idStr, 'GET');
                        $this->cache->save($key, $status, 3600);
                }

                return $status;
        }
}
