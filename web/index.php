<?php
function getMime($ext) {
    $types = [
        'woff'=>'application/x-font-woff',
        'woff2'=>'application/x-font-woff2',
        'eot'=>'application/vnd.ms-fontobject',
        'svg'=>'image/svg+xml',
        'ttf'=>'application/x-font-ttf',
    ];
    if (isset($types[$ext])) {
        return $types[$ext];
    }
}
if (PHP_SAPI == 'cli-server') {
    $file = __DIR__ . $_SERVER['REQUEST_URI'];
    $path = pathinfo($_SERVER['SCRIPT_FILENAME']);
    $mime = getMime($path['extension']);
    if ($mime) {
        header('Content-type: '.$mime);
        readfile($_SERVER['SCRIPT_FILENAME']);
    } elseif (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register middleware
require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../src/routes.php';

// Register route midlleware
require __DIR__ . '/../src/routes-middleware.php';

// Run app
$app->run();
