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

    $view = new \Slim\Views\Twig($settings['templates'], [
        'cache' => $settings['cache'],
        'debug' => true,
    ]);

    $view->addExtension(new \Slim\Views\TwigExtension(
        $container['router'],
        $container['request']->getUri()
    ));

    $view->addExtension(new nanotwi\Views\TwigExtensionAutologinPath($container));

    return $view;
};

// Twig views
$container['cache'] = function ($container) {
    $settings = $container->get('settings')['cache'];
    $cache = new FilesystemCache($settings['path']);
    $cache->setNamespace('twitter_');
    return $cache;
};


// High-level Twitter API
$container['nanoTwitter'] = function ($container) {
    $settings = $container->get('settings')['twitter'];

    $cache = $container->get('cache');

    return new \nanotwi\NanoTwitter($settings, $cache);
};


// Twitter OAuth
$container['oAuthFlow'] = function ($container) {
    $settings = $container->get('settings')['twitter'];

    return new \nanotwi\OAuthFlow($settings['consumerKey'], $settings['consumerSecret']);
};

// Auto-login link
$container['autoLogin'] = function ($container) {
    $settings = $container->get('settings')['twitter'];

    return new \nanotwi\AutoLogin($settings['autologinKey']);
};
