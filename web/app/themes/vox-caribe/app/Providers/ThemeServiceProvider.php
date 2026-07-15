<?php

namespace App\Providers;

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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // Register homepage view composer
        $this->registerViewComposers();
    }

    /**
     * Register view composers.
     */
    private function registerViewComposers(): void
    {
        $view = $this->app->make('view');

        $view->composer('front-page', \App\View\Composers\FrontPage::class);
    }
}
