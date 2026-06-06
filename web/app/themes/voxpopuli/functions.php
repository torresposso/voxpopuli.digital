<?php

use App\Providers\SeoServiceProvider;
use Roots\Acorn\Application;
use Roots\Acorn\Sage\SageServiceProvider;

if (! file_exists($composer = __DIR__.'/vendor/autoload.php')) {
    wp_die(__('Error locating autoloader. Please run <code>composer install</code>.', 'voxpopuli'));
}

require $composer;

$app = Application::configure()
    ->withRouting(wordpress: true)
    ->withProviders([
        SageServiceProvider::class,
        SeoServiceProvider::class,
    ])
    ->boot();

// Load theme configurations
if (file_exists($setup = __DIR__.'/app/setup.php')) {
    require_once $setup;
}

if (file_exists($filters = __DIR__.'/app/filters.php')) {
    require_once $filters;
}

return $app;
