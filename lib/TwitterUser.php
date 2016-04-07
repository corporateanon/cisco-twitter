<?php

namespace nanotwi;

class TwitterUser {

    public $id;
    public $screenName;
    public $oauthToken;
    public $oauthTokenSecret;

    public function __construct($user) {
        $this->screenName = $user['screen_name'];
        $this->oauthToken = $user['oauth_token'];
        $this->oauthTokenSecret = $user['oauth_token_secret'];
        $this->id = $user['user_id'];
    }


}
