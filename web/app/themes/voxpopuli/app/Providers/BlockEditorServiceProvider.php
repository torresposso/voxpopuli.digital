<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class BlockEditorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * Inject styles into the block editor.
         */
        add_filter('block_editor_settings_all', function ($settings) {
            $style = Vite::asset('resources/css/editor.css');

            $settings['styles'][] = [
                'css' => "@import url('{$style}')",
            ];

            return $settings;
        });

        /**
         * Enqueue Google Fonts inside the block editor iframe.
         */
        add_action('enqueue_block_editor_assets', function () {
            wp_enqueue_style(
                'voxpopuli-editor-fonts',
                'https://fonts.googleapis.com/css2?family=Literata:ital,opsz,wght@0,7..72,200..900;1,7..72,200..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap',
                [],
                null,
            );
        });

        /**
         * Inject scripts into the block editor.
         */
        add_action('admin_head', function () {
            if (! get_current_screen()?->is_block_editor()) {
                return;
            }

            if (! Vite::isRunningHot()) {
                $dependencies = json_decode(Vite::content('editor.deps.json'));

                foreach ($dependencies as $dependency) {
                    if (! wp_script_is($dependency)) {
                        wp_enqueue_script($dependency);
                    }
                }
            }
            echo Vite::withEntryPoints([
                'resources/js/editor.js',
            ])->toHtml();
        });

        /**
         * Disable on-demand block asset loading.
         *
         * @link https://core.trac.wordpress.org/ticket/61965
         */
        add_filter('should_load_separate_core_block_assets', '__return_false');
    }
}
