<?php

namespace App\Providers;

use App\Seo\JsonLd;
use App\Vite;
use Roots\Acorn\Assets\Vite as SageVite;
use Roots\Acorn\Sage\SageServiceProvider;

class ThemeServiceProvider extends SageServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        // Bind SEO services as singletons
        $this->app->singleton(JsonLd::class, function () {
            return new JsonLd();
        });

        // Override Vite with our custom class for dynamic HMR host resolution
        $this->app->singleton(SageVite::class, function () {
            return new Vite();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // Register view composers explicitly
        $this->registerViewComposers();

    }

    /**
     * Register view composers.
     *
     * Sage 11 auto-discovers composers via the static $views property,
     * but explicit registration makes dependencies visible and allows
     * easy replacement in tests.
     */
    private function registerViewComposers(): void
    {
        $view = $this->app->make('view');

        $view->composer('*', \App\View\Composers\App::class);
        $view->composer('partials.page-header', \App\View\Composers\Post::class);
        $view->composer('partials.content*', \App\View\Composers\Post::class);
        $view->composer('partials.comments', \App\View\Composers\Comments::class);
        $view->composer('front-page', \App\View\Composers\FrontPage::class);
        $view->composer('layouts.app', \App\View\Composers\Seo::class);
        $view->composer('category', \App\View\Composers\Category::class);
    }
}
