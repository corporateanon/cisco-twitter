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
        $keyHash = \md5($this->autoLoginKey, true);
        $encrypted = \openssl_encrypt($tokens, self::ENCRYPTION_METHOD, $keyHash, \OPENSSL_RAW_DATA);
        return \bin2hex($encrypted);
    }

    public function parseAutologinToken($token) {
        $keyHash = md5($this->autoLoginKey, true);
        if(!preg_match('/^[0-9a-fA-F]+$/', $token)) {
            throw new AutologinException('Could not parse auto-login token');
        }
        $encrypted = \hex2bin($token);
        $tokens = \openssl_decrypt($encrypted, self::ENCRYPTION_METHOD, $keyHash, \OPENSSL_RAW_DATA);
        if ($tokens === false) {
            throw new AutologinException('Could not decrypt auto-login token');
        }
        if (strpos($tokens, self::SEPARATOR) === false) {
            throw new AutologinException('Could not parse auto-login token');
        }
        list($oAuthToken, $oAuthTokenSecret) = \explode(self::SEPARATOR, $tokens);
        return [
            'oAuthToken' => $oAuthToken,
            'oAuthTokenSecret' => $oAuthTokenSecret
        ];
    }

}
