<?php

$root = __DIR__ . '/..';

$dotenv = new Dotenv\Dotenv($root);
$dotenv->load();


return [
    'settings' => [
        'displayErrorDetails' => true,

        // Monolog settings
        'logger' => [
            'name' => 'nanotwi',
            'path' => $root . '/logs/app.log',
        ],

        // Data cache settings
        'cache' => [
            'path' => $root . '/var/data-cache/',
        ],

        // Templates
        'view' => [
            'cache' => $root . '/var/template-cache/',
        ],

        // Twitter authentication settings
        'twitter' => [
            'consumerKey'       => getenv('TWITTER_CONSUMER_KEY'),
            'consumerSecret'    => getenv('TWITTER_CONSUMER_SECRET'),
            'accessToken'       => getenv('TWITTER_ACCESS_TOKEN'),
            'accessTokenSecret' => getenv('TWITTER_ACCESS_TOKEN_SECRET'),
        ],
    ],
];
