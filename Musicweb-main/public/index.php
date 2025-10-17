<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Hide PHP notices/warnings in production to avoid noisy output
if (getenv('APP_DEBUG') === 'false' || getenv('APP_ENV') === 'production') {
    if (function_exists('ini_set')) {
        ini_set('display_errors', '0');
    }
    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED & ~E_STRICT);
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
