<?php

namespace nanotwi;

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
}
