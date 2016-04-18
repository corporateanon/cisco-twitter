<?php

namespace nanotwi;

use Abraham\TwitterOAuth\TwitterOAuth;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class OAuthFlow {

    private $consumerSecret;
    private $consumerKey;

    public function __construct($consumerKey, $consumerSecret) {
        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;
    }

    public function startAuthentication(ResponseInterface $res, $callback) {
        $requestToken = $this->getRequestToken($callback);
        $_SESSION['oauth_token'] = $requestToken['oauth_token'];
        $_SESSION['oauth_token_secret'] = $requestToken['oauth_token_secret'];
        $url = $this->getConnection()->url('oauth/authorize', ['oauth_token' => $requestToken['oauth_token']]);
        return $res->withStatus(302)
                        ->withHeader('Location', $url);
    }

    public function completeAuthentication(ServerRequestInterface $req) {
        $oAuthToken = $_SESSION['oauth_token'];
        $oAuthTokenSecret = $_SESSION['oauth_token_secret'];
        $conn = $this->getConnection($oAuthToken, $oAuthTokenSecret);


        $query = $req->getQueryParams();
        $verifier = $query['oauth_verifier'];
        
        $accessToken = $conn->oauth('oauth/access_token', ['oauth_verifier' => $verifier]);
        $_SESSION['twitterUser'] = $accessToken;
    }

    /**
     * @return TwitterOAuth
     */
    protected function getConnection($oAuthToken = null, $oAuthTokenSecret = null) {
        return new TwitterOAuth($this->consumerKey, $this->consumerSecret, $oAuthToken, $oAuthTokenSecret);
    }

    protected function getRequestToken($callback = 'oob') {
        $conn = new TwitterOAuth($this->consumerKey, $this->consumerSecret);
        return $conn->oauth('oauth/request_token', [ 'oauth_callback' => $callback]);
    }
    

}

class OAuthFlowException extends \Exception {
    
}

class OAuthFlowInvalidKeyException extends OAuthFlowException {
    
}
