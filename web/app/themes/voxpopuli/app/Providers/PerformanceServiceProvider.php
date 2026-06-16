<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PerformanceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * Calculate and save word count and reading time on post save.
         */
        add_action('save_post', function ($post_id) {
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }
            if (wp_is_post_revision($post_id)) {
                return;
            }

            $content = get_post_field('post_content', $post_id);
            $word_count = str_word_count(strip_tags($content));
            $reading_time = max(1, ceil($word_count / 200));

            update_post_meta($post_id, 'vp_word_count', $word_count);
            update_post_meta($post_id, 'vp_reading_time', $reading_time);
        }, 10, 1);

        /**
         * Invalidate Hero and Homepage sections caches when a post is saved or deleted.
         */
        add_action('save_post', function () {
            delete_transient(\App\View\Components\Hero::getCacheKey());
            (new \App\View\Composers\Index())->bustCache();
        });
        add_action('deleted_post', function () {
            delete_transient(\App\View\Components\Hero::getCacheKey());
            (new \App\View\Composers\Index())->bustCache();
        });
    }
}
