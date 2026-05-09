<?php

declare(strict_types=1);

foreach ([
    '/tmp/laravel',
    '/tmp/laravel/bootstrap',
    '/tmp/laravel/bootstrap/cache',
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

foreach ([
    'APP_CONFIG_CACHE' => '/tmp/laravel/bootstrap/cache/config.php',
    'APP_EVENTS_CACHE' => '/tmp/laravel/bootstrap/cache/events.php',
    'APP_PACKAGES_CACHE' => '/tmp/laravel/bootstrap/cache/packages.php',
    'APP_ROUTES_CACHE' => '/tmp/laravel/bootstrap/cache/routes.php',
    'APP_SERVICES_CACHE' => '/tmp/laravel/bootstrap/cache/services.php',
] as $key => $value) {
    $_ENV[$key] = $_ENV[$key] ?? $value;
    $_SERVER[$key] = $_SERVER[$key] ?? $value;
}

try {
    require __DIR__.'/../public/index.php';
} catch (Throwable $exception) {
    error_log((string) $exception);

    throw $exception;
}
