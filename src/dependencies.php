<?php
use \Doctrine\Common\Cache\FilesystemCache;

// DIC configuration

$container = $app->getContainer();

// monolog
$container['logger'] = function ($container) {
    $settings = $container->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};

// Twig views
$container['view'] = function ($container) {
    $settings = $container->get('settings')['view'];

    $view = new \Slim\Views\Twig('templates', [
        'cache' => $settings['cache'],
        'debug' => true,
    ]);

    $view->addExtension(new \Slim\Views\TwigExtension(
        $container['router'],
        $container['request']->getUri()
    ));

    return $view;
};

// Twig views
$container['twitter'] = function ($container) {
    $settings = $container->get('settings')['twitter'];

    $twitter = new Twitter(
        $settings['consumerKey'],
        $settings['consumerSecret'],
        $settings['accessToken'],
        $settings['accessTokenSecret']
    );

    return $twitter;
};

// Twig views
$container['cache'] = function ($container) {
    $settings = $container->get('settings')['cache'];
    $cache = new FilesystemCache($settings['path']);
    $cache->setNamespace('twitter_');
    return $cache;
};
