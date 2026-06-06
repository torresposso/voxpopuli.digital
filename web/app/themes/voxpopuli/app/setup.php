<?php

/**
 * Theme setup.
 */

namespace App;

use App\View\Components\Hero;
use App\View\Composers\Index;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Vite;

// Register our custom Vite class to dynamically rewrite Hot Asset URLs in memory
$vite = new \App\Vite;
$vite->useHotFile(__DIR__.'/../public/hot');

// Set both bindings and instances to override any previously resolved instances in Acorn/Laravel
app()->instance(\Roots\Acorn\Assets\Vite::class, $vite);
app()->instance(\Illuminate\Foundation\Vite::class, $vite);
app()->instance('assets.vite', $vite);

app()->singleton(\Roots\Acorn\Assets\Vite::class, fn () => $vite);
app()->singleton(\Illuminate\Foundation\Vite::class, fn () => $vite);
app()->singleton('assets.vite', fn () => $vite);

// Register class-based Blade components explicitly
Blade::component(Hero::class, 'hero');

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
 * Enqueue Google Fonts inside the block editor iframe.
 *
 * @return void
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
    delete_transient(Hero::getCacheKey());
    (new Index)->bustCache();
});
add_action('deleted_post', function () {
    delete_transient(Hero::getCacheKey());
    (new Index)->bustCache();
});

/**
 * Inyectar Google Analytics (GA4) y Meta Pixel (Facebook/Instagram) de forma segura en producción
 */
add_action('wp_head', function () {
    // 1. Cargar las credenciales desde las variables de entorno de Bedrock
    $ga_id = env('GOOGLE_ANALYTICS_ID');
    $meta_id = env('META_PIXEL_ID');

    // Meta Domain Verification (requerido por Facebook para validar la propiedad del dominio)
    echo '
    <!-- Meta Domain Verification -->
    <meta name="facebook-domain-verification" content="pl4dsq30p15llps9quh6k37uq6l8hn" />
    ';

    // 2. Solo cargar en producción y si el usuario no está logueado para no ensuciar métricas
    if (WP_ENV === 'production' && ! is_user_logged_in()) {

        // Google Analytics (GA4)
        if ($ga_id) {
            echo "
            <!-- Google tag (gtag.js) -->
            <script async src=\"https://www.googletagmanager.com/gtag/js?id={$ga_id}\"></script>
            <script>
              window.dataLayer = window.dataLayer || [];
              function gtag(){dataLayer.push(arguments);}
              gtag('js', new Date());
              gtag('config', '{$ga_id}', { 'anonymize_ip': true });
            </script>
            ";
        }

        // Meta Pixel (Facebook / Instagram)
        if ($meta_id) {
            echo "
            <!-- Meta Pixel Code -->
            <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{$meta_id}');
            fbq('track', 'PageView');
            </script>
            <noscript><img height=\"1\" width=\"1\" style=\"display:none\"
            src=\"https://www.facebook.com/tr?id={$meta_id}&ev=PageView&noscript=1\"
            /></noscript>
            <!-- End Meta Pixel Code -->
            ";
        }
    }
}, 1); // Prioridad 1 para cargar al inicio del <head>
