<?php

declare(strict_types=1);

foreach ([
    '/tmp/laravel',
    '/tmp/laravel/bootstrap',
    '/tmp/laravel/bootstrap/cache',
    '/tmp/laravel/app',
    '/tmp/laravel/app/private',
    '/tmp/laravel/app/private/livewire-tmp',
    '/tmp/laravel/app/public',
    '/tmp/laravel/app/public/livewire-tmp',
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
    'APP_CONFIG_CACHE' => [
        'built' => __DIR__.'/../bootstrap/cache/config.php',
        'tmp' => '/tmp/laravel/bootstrap/cache/config.php',
    ],
    'APP_EVENTS_CACHE' => [
        'built' => __DIR__.'/../bootstrap/cache/events.php',
        'tmp' => '/tmp/laravel/bootstrap/cache/events.php',
    ],
    'APP_PACKAGES_CACHE' => [
        'built' => __DIR__.'/../bootstrap/cache/packages.php',
        'tmp' => '/tmp/laravel/bootstrap/cache/packages.php',
    ],
    'APP_ROUTES_CACHE' => [
        'built' => __DIR__.'/../bootstrap/cache/routes.php',
        'tmp' => '/tmp/laravel/bootstrap/cache/routes.php',
    ],
    'APP_SERVICES_CACHE' => [
        'built' => __DIR__.'/../bootstrap/cache/services.php',
        'tmp' => '/tmp/laravel/bootstrap/cache/services.php',
    ],
] as $key => $value) {
    $path = is_file($value['built']) ? $value['built'] : $value['tmp'];

    $_ENV[$key] = $_ENV[$key] ?? $path;
    $_SERVER[$key] = $_SERVER[$key] ?? $path;
}

try {
    require __DIR__.'/../public/index.php';
} catch (Throwable $exception) {
    error_log((string) $exception);

    throw $exception;
}
