<?php

use App\Providers\AnalyticsServiceProvider;
use App\Providers\BlockEditorServiceProvider;
use App\Providers\PerformanceServiceProvider;
use App\Providers\SeoServiceProvider;
use App\Providers\ThemeServiceProvider;
use Roots\Acorn\Application;
use Roots\Acorn\Sage\SageServiceProvider;

if (! file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
    wp_die(__('Error locating autoloader. Please run <code>composer install</code>.', 'acta-populi'));
}

require $composer;

$app = Application::configure()
    ->withRouting(wordpress: true)
    ->withProviders([
        SageServiceProvider::class,
        ThemeServiceProvider::class,
        BlockEditorServiceProvider::class,
        PerformanceServiceProvider::class,
        AnalyticsServiceProvider::class,
        SeoServiceProvider::class,
    ])
    ->boot();

// Load theme configurations
if (file_exists($filters = __DIR__ . '/app/filters.php')) {
    require_once $filters;
}

return $app;
