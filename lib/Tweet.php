<?php

namespace nanotwi;

use Underscore\Types\Strings;


class Tweet {
        function __construct($status) {
                $this->status = $status;
                $this->readable = \Twitter::clickable($status);
                $this->link = "https://twitter.com/{$status->user->screen_name}/status/{$status->id_str}";
                $this->mobileLink = "https://mobile.twitter.com/{$status->user->screen_name}/status/{$status->id_str}";
        }

        public function getPictures() {
                if(!property_exists($this->status, 'extended_entities')) {
                        return Array();
                }
                $entities = $this->status->extended_entities;
                if (!$entities) {
                        return Array();
                }
                $media = $entities->media;
                if (!$media) {
                        return Array();
                }

                $pics = array_filter($media, function ($item) {
                        return $item->type === 'photo';
                });

                return $pics;
        }

        function getTextChunks() {
                return self::chunkSplitUnicode($this->getText(), 64);
        }

        function getText() {
                if ($this->status->retweeted_status) {
                        return $this->status->retweeted_status->text;
                }
                return $this->status->text;
        }

        protected static function chunkSplitUnicode($str, $l) {
                return array_map(function ($aChunk) {
                        return join($aChunk, '');
                }, array_chunk(preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY), $l));
        }
}
