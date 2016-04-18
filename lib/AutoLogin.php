<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace nanotwi;

/**
 * Description of Autologin
 *
 * @author mk
 */
class AutoLogin {

    private $autoLoginKey;

    const ENCRYPTION_METHOD = 'AES-256-CTR';
    const SEPARATOR = ':';

    public function __construct($autoLoginKey) {
        $this->autoLoginKey = $autoLoginKey;
    }

    public function createAutologinToken(TwitterUser $user) {
        $tokens = $user->oAuthToken . self::SEPARATOR . $user->oAuthTokenSecret;
        $keyHash = md5($this->autoLoginKey, true);
        $base64 = openssl_encrypt($tokens, self::ENCRYPTION_METHOD, $keyHash);
        return urlencode($base64);
    }

    public function parseAutologinToken($token) {
        $keyHash = md5($this->autoLoginKey, true);
        $base64 = urldecode($token);
        $tokens = openssl_decrypt($base64, self::ENCRYPTION_METHOD, $keyHash);
        if ($tokens === false) {
            throw new AutologinException('Could not decrypt auto-login token');
        }
        if (strpos($tokens, self::SEPARATOR) === false) {
            throw new AutologinException('Could not parse auto-login token');
        }
        list($oAuthToken, $oAuthTokenSecret) = split(self::SEPARATOR, $tokens);
        return [
            'oAuthToken' => $oAuthToken,
            'oAuthTokenSecret' => $oAuthTokenSecret
        ];
    }

}
