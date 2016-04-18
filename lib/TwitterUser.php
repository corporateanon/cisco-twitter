<?php

namespace nanotwi;

class TwitterUser {

    public $id;
    public $screenName;
    public $oAuthToken;
    public $oAuthTokenSecret;

    public function __construct($user) {
        $this->screenName = $user['screen_name'];
        $this->oAuthToken = $user['oauth_token'];
        $this->oAuthTokenSecret = $user['oauth_token_secret'];
        $this->id = $user['user_id'];
    }
}
