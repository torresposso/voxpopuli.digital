<?php

/**
 * Theme setup.
 */

namespace App;

use Illuminate\Support\Facades\Vite;

// Register our custom Vite class to dynamically rewrite Hot Asset URLs in memory
$vite = new \App\Vite();
$vite->useHotFile(__DIR__ . '/../public/hot');

// Set both bindings and instances to override any previously resolved instances in Acorn/Laravel
app()->instance(\Roots\Acorn\Assets\Vite::class, $vite);
app()->instance(\Illuminate\Foundation\Vite::class, $vite);
app()->instance('assets.vite', $vite);

app()->singleton(\Roots\Acorn\Assets\Vite::class, fn() => $vite);
app()->singleton(\Illuminate\Foundation\Vite::class, fn() => $vite);
app()->singleton('assets.vite', fn() => $vite);

// Register class-based Blade components explicitly
\Illuminate\Support\Facades\Blade::component(\App\View\Components\Hero::class, 'hero');

/**
 * Inject styles into the block editor.
 *
 * @return array
 */
add_filter('block_editor_settings_all', function ($settings) {
    $style = Vite::asset('resources/css/editor.css');

    $settings['styles'][] = [
        'css' => "@import url('{$style}')",
    ];

    return $settings;
});

/**
 * Inject scripts into the block editor.
 *
 * @return void
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
 * Use the generated theme.json file.
 *
 * @return string
 */
add_filter('theme_file_path', function ($path, $file) {
    return $file === 'theme.json'
        ? public_path('build/assets/theme.json')
        : $path;
}, 10, 2);

/**
 * Disable on-demand block asset loading.
 *
 * @link https://core.trac.wordpress.org/ticket/61965
 */
add_filter('should_load_separate_core_block_assets', '__return_false');

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Disable intermediate image sizes (thumbnails).
     *
     * @link https://developer.wordpress.org/reference/hooks/intermediate_image_sizes/
     */
    // add_filter('intermediate_image_sizes', function ($sizes) {
    //     return ['thumbnail'];
    // });
    // Scale oversized uploads down to 2560px (WordPress default)
    // Remove the filter that returned false to restore default behavior

    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'voxpopuli'),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');
}, 20);

/**
 * Register the theme sidebars.
 *
 * @return void
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
