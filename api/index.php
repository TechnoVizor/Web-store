<?php

declare(strict_types=1);

foreach ([
    '/tmp/laravel',
    '/tmp/laravel/framework',
    '/tmp/laravel/framework/cache',
    '/tmp/laravel/framework/cache/data',
    '/tmp/laravel/framework/sessions',
    '/tmp/laravel/framework/testing',
    '/tmp/laravel/framework/views',
    '/tmp/laravel/logs',
] as $path) {
    if (! is_dir($path)) {
        mkdir($path, 0777, true);
    }
}

$_ENV['LARAVEL_STORAGE_PATH'] = $_ENV['LARAVEL_STORAGE_PATH'] ?? '/tmp/laravel';
$_SERVER['LARAVEL_STORAGE_PATH'] = $_SERVER['LARAVEL_STORAGE_PATH'] ?? '/tmp/laravel';
$_ENV['VIEW_COMPILED_PATH'] = $_ENV['VIEW_COMPILED_PATH'] ?? '/tmp/laravel/framework/views';
$_SERVER['VIEW_COMPILED_PATH'] = $_SERVER['VIEW_COMPILED_PATH'] ?? '/tmp/laravel/framework/views';

require __DIR__.'/../public/index.php';
