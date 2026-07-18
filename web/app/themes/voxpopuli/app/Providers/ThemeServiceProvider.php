<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register our custom Vite class to dynamically rewrite Hot Asset URLs in memory
        $vite = new \App\Vite();
        $vite->useHotFile(__DIR__ . '/../../public/hot');

        // Set both bindings and instances to override any previously resolved instances in Acorn/Laravel
        $this->app->instance(\Roots\Acorn\Assets\Vite::class, $vite);
        $this->app->instance(\Illuminate\Foundation\Vite::class, $vite);
        $this->app->instance('assets.vite', $vite);

        $this->app->singleton(\Roots\Acorn\Assets\Vite::class, fn() => $vite);
        $this->app->singleton(\Illuminate\Foundation\Vite::class, fn() => $vite);
        $this->app->singleton('assets.vite', fn() => $vite);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register class-based Blade components explicitly
        \Illuminate\Support\Facades\Blade::component(\App\View\Components\Hero::class, 'hero');

        /**
         * Use the generated theme.json file.
         */
        add_filter('theme_file_path', function ($path, $file) {
            return $file === 'theme.json'
                ? public_path('build/assets/theme.json')
                : $path;
        }, 10, 2);

        /**
         * Register the initial theme setup.
         */
        add_action('after_setup_theme', function () {
            remove_theme_support('block-templates');

            register_nav_menus([
                'primary_navigation' => __('Primary Navigation', 'voxpopuli'),
            ]);

            remove_theme_support('core-block-patterns');
            add_theme_support('title-tag');
            add_theme_support('post-thumbnails');
            add_theme_support('responsive-embeds');

            add_theme_support('html5', [
                'caption',
                'comment-form',
                'comment-list',
                'gallery',
                'search-form',
                'script',
                'style',
            ]);

            add_theme_support('customize-selective-refresh-widgets');
        }, 20);

        /**
         * Register the theme sidebars.
         */
        add_action('widgets_init', function () {
            $config = [
                'before_widget' => '<section class="widget %1$s %2$s">',
                'after_widget' => '</section>',
                'before_title' => '<h3>',
                'after_title' => '</h3>',
            ];

            register_sidebar([
                'name' => __('Primary', 'voxpopuli'),
                'id' => 'sidebar-primary',
            ] + $config);

            register_sidebar([
                'name' => __('Footer', 'voxpopuli'),
                'id' => 'sidebar-footer',
            ] + $config);
        });

        /**
         * Surgical cache invalidation for homepage sections, hero component, and drawer featured post when publishing or updating posts.
         */
        $invalidateCache = function () {
            delete_transient(\App\View\Components\Hero::getCacheKey());
            \Illuminate\Support\Facades\Cache::forget('voxpopuli_homepage_sections_ids');
            \Illuminate\Support\Facades\Cache::forget('voxpopuli_drawer_featured_post');
        };

        add_action('save_post', $invalidateCache);
        add_action('transition_post_status', function ($new_status, $old_status) use ($invalidateCache) {
            if ($new_status !== $old_status) {
                $invalidateCache();
            }
        }, 10, 2);
    }
}
