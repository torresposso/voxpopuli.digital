<?php

namespace App\Providers;

use App\Seo\JsonLd;
use App\Seo\Migration;
use App\Seo\Sitemap;
use App\View\Composers\Seo as SeoComposer;
use Illuminate\Support\ServiceProvider;
use WP_Query;

class SeoServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(JsonLd::class, function () {
            return new JsonLd();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register the View Composer for SEO data injection (Sage Blade layouts)
        $this->app->make('view')->composer('layouts.app', SeoComposer::class);

        // Render SEO meta tags in wp_head for non-Blade themes (e.g. SmartMag)
        add_action('wp_head', function () {
            $composer = $this->app->make(SeoComposer::class);
            $data = $composer->with();
            if (! empty($data['seoMetaTags'])) {
                echo $data['seoMetaTags'] . "\n";
            }
            if (! empty($data['seoJsonLd'])) {
                echo $data['seoJsonLd'] . "\n";
            }
        }, 1);

        // Alternative: Direct wp_head output for SEO tags (when Sage Blade layout is not used)
        add_action('wp_head', function () {
            $data = [];
            if (is_singular()) {
                $postId = get_queried_object_id();
                $data = [
                    'post_title' => get_the_title($postId),
                    'post_url' => get_permalink($postId),
                    'meta_desc' => get_post_meta($postId, '_voxpopuli_meta_desc', true) ?: null,
                    'og_title' => get_post_meta($postId, '_voxpopuli_og_title', true) ?: null,
                    'og_desc' => get_post_meta($postId, '_voxpopuli_og_desc', true) ?: null,
                    'og_image_url' => null,
                    'noindex' => get_post_meta($postId, '_voxpopuli_noindex', true) === '1',
                    'canonical' => get_post_meta($postId, '_voxpopuli_canonical', true) ?: null,
                    'post_url' => get_permalink($postId),
                ];
                $ogImageId = get_post_meta($postId, '_voxpopuli_og_image', true);
                if (empty($ogImageId)) {
                    $ogImageId = get_post_thumbnail_id($postId);
                }
                if ($ogImageId) {
                    $data['og_image_url'] = wp_get_attachment_url((int) $ogImageId);
                }
            } elseif (is_front_page()) {
                $data = [
                    'post_title' => get_bloginfo('name'),
                    'post_url' => home_url('/'),
                    'meta_desc' => get_bloginfo('description'),
                    'og_title' => get_bloginfo('name'),
                    'og_desc' => get_bloginfo('description'),
                    'og_image_url' => null,
                    'noindex' => false,
                    'canonical' => home_url('/'),
                ];
            }

            if (empty($data)) {
                return;
            }

            $seo = new \App\Seo\SeoMeta($data);
            $renderer = new \App\Seo\MetaRenderer($seo);
            echo $renderer->render() . "\n";

            // JSON-LD
            $siteUrl = home_url();
            $siteName = get_bloginfo('name');
            $jsonld = app(\App\Seo\JsonLd::class);
            $graph = [];

            $org = $jsonld->organization(['name' => $siteName, 'url' => $siteUrl]);
            unset($org['@context']);
            $graph[] = $org;

            $site = $jsonld->website(['name' => $siteName, 'url' => $siteUrl, 'search_url' => $siteUrl . '/?s={search_term_string}']);
            unset($site['@context']);
            $graph[] = $site;

            if (is_singular('post')) {
                $article = $jsonld->article([
                    'headline' => $data['post_title'] ?? '',
                    'description' => $data['meta_desc'] ?? $data['og_desc'] ?? '',
                    'url' => $data['post_url'] ?? '',
                ]);
                unset($article['@context']);
                $graph[] = $article;
            }

            $graph[] = $jsonld->breadcrumbList([['name' => 'Inicio', 'url' => $siteUrl]]);

            echo \App\Seo\JsonLd::toScript(['@context' => 'https://schema.org', '@graph' => $graph]) . "\n";
        }, 1);

        // Register admin meta boxes
        add_action('add_meta_boxes', function () {
            $this->registerMetaBoxes();
        });

        // Handle meta box field saving
        add_action('save_post', function (int $postId) {
            $this->saveMetaBoxFields($postId);
        });

        // Disable WordPress core sitemaps — we use a custom one
        add_filter('wp_sitemaps_enabled', '__return_false');

        // Register sitemap rewrite rule
        add_action('init', function () {
            $this->registerSitemapRewrite();
        });

        // Handle sitemap requests
        add_action('template_redirect', function () {
            $this->handleSitemapRequest();
        });

        // Flush rewrite rules on theme activation
        add_action('after_switch_theme', function () {
            $this->registerSitemapRewrite();
            flush_rewrite_rules();
        });

        // Register WP-CLI command
        if (defined('WP_CLI') && \WP_CLI) {
            \WP_CLI::add_command('voxpopuli migrate-seo', [Migration::class, 'handle']);
        }
    }

    /**
     * Register SEO meta boxes on post and page edit screens.
     */
    private function registerMetaBoxes(): void
    {
        $screens = ['post', 'page'];

        foreach ($screens as $screen) {
            add_meta_box(
                'voxpopuli_seo',
                'Vox Populi SEO',
                function ($post) {
                    $this->renderMetaBox($post);
                },
                $screen,
                'normal',
                'high',
            );
        }
    }

    /**
     * Render the SEO meta box fields.
     *
     * @param  \WP_Post  $post
     */
    private function renderMetaBox($post): void
    {
        wp_nonce_field('voxpopuli_seo_save', 'voxpopuli_seo_nonce');

        $metaDesc = get_post_meta($post->ID, '_voxpopuli_meta_desc', true);
        $ogTitle = get_post_meta($post->ID, '_voxpopuli_og_title', true);
        $ogDesc = get_post_meta($post->ID, '_voxpopuli_og_desc', true);
        $ogImage = get_post_meta($post->ID, '_voxpopuli_og_image', true);
        $noindex = get_post_meta($post->ID, '_voxpopuli_noindex', true);
        $canonical = get_post_meta($post->ID, '_voxpopuli_canonical', true);
        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="voxpopuli_meta_desc">Meta Description</label></th>
                <td>
                    <textarea id="voxpopuli_meta_desc" name="_voxpopuli_meta_desc" rows="3" class="large-text" maxlength="160"><?php echo esc_textarea($metaDesc); ?></textarea>
                    <p class="description">Maximum 160 characters.</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="voxpopuli_og_title">OG Title</label></th>
                <td>
                    <input type="text" id="voxpopuli_og_title" name="_voxpopuli_og_title" value="<?php echo esc_attr($ogTitle); ?>" class="large-text">
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="voxpopuli_og_desc">OG Description</label></th>
                <td>
                    <textarea id="voxpopuli_og_desc" name="_voxpopuli_og_desc" rows="3" class="large-text"><?php echo esc_textarea($ogDesc); ?></textarea>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="voxpopuli_og_image">OG Image</label></th>
                <td>
                    <input type="text" id="voxpopuli_og_image" name="_voxpopuli_og_image" value="<?php echo esc_attr($ogImage); ?>" class="large-text">
                    <button type="button" class="button" id="voxpopuli_og_image_button">Select Image</button>
                    <p class="description">Enter attachment ID or use the media button.</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="voxpopuli_noindex">Noindex</label></th>
                <td>
                    <label>
                        <input type="checkbox" id="voxpopuli_noindex" name="_voxpopuli_noindex" value="1" <?php checked($noindex, '1'); ?>>
                        Prevent search engines from indexing this page
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="voxpopuli_canonical">Canonical URL</label></th>
                <td>
                    <input type="url" id="voxpopuli_canonical" name="_voxpopuli_canonical" value="<?php echo esc_attr($canonical); ?>" class="large-text">
                </td>
            </tr>
        </table>
        <script>
        (function($) {
            $('#voxpopuli_og_image_button').on('click', function(e) {
                e.preventDefault();
                var frame = wp.media({
                    title: 'Select OG Image',
                    multiple: false,
                    library: { type: 'image' }
                });
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $('#voxpopuli_og_image').val(attachment.id);
                });
                frame.open();
            });
        })(jQuery);
        </script>
        <?php
    }

    /**
     * Save meta box fields.
     */
    private function saveMetaBoxFields(int $postId): void
    {
        // Verify nonce
        if (! isset($_POST['voxpopuli_seo_nonce'])
            || ! wp_verify_nonce($_POST['voxpopuli_seo_nonce'], 'voxpopuli_seo_save')) {
            return;
        }

        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check user capability
        if (! current_user_can('edit_post', $postId)) {
            return;
        }

        $fields = [
            '_voxpopuli_meta_desc',
            '_voxpopuli_og_title',
            '_voxpopuli_og_desc',
            '_voxpopuli_og_image',
            '_voxpopuli_noindex',
            '_voxpopuli_canonical',
        ];

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = wp_unslash($_POST[$field]);

                // Sanitize by field type
                if ($field === '_voxpopuli_meta_desc' || $field === '_voxpopuli_og_desc') {
                    $value = sanitize_textarea_field($value);
                } elseif ($field === '_voxpopuli_canonical') {
                    if ($value !== '' && (! filter_var($value, FILTER_VALIDATE_URL)
                        || ! (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')))) {
                        delete_post_meta($postId, $field);
                        continue;
                    }
                    $value = esc_url_raw($value);
                } elseif ($field === '_voxpopuli_og_image') {
                    $value = absint($value);
                } elseif ($field === '_voxpopuli_noindex') {
                    $value = '1';
                } else {
                    $value = sanitize_text_field($value);
                }

                // Truncate meta description
                if ($field === '_voxpopuli_meta_desc' && mb_strlen($value) > 160) {
                    $value = mb_substr($value, 0, 160);
                }

                update_post_meta($postId, $field, $value);
            } else {
                // Checkbox not checked → delete noindex
                if ($field === '_voxpopuli_noindex') {
                    delete_post_meta($postId, $field);
                }
            }
        }
    }

    /**
     * Register rewrite rule for /sitemap.xml.
     */
    private function registerSitemapRewrite(): void
    {
        add_rewrite_rule(
            '^sitemap\.xml$',
            'index.php?vox_sitemap=1',
            'top',
        );

        add_rewrite_tag('%vox_sitemap%', '1');
    }

    /**
     * Handle sitemap requests.
     */
    private function handleSitemapRequest(): void
    {
        if (get_query_var('vox_sitemap') !== '1') {
            return;
        }

        // Fetch all published posts and pages
        $query = new WP_Query([
            'post_type' => ['post', 'page'],
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'modified',
            'order' => 'DESC',
        ]);

        $entries = [];

        while ($query->have_posts()) {
            $query->the_post();
            $postId = get_the_ID();

            // Skip noindexed posts
            if (get_post_meta($postId, '_voxpopuli_noindex', true) === '1') {
                continue;
            }

            $entries[] = [
                'loc' => get_permalink($postId),
                'lastmod' => get_the_modified_date('Y-m-d', $postId),
                'priority' => get_post_type($postId) === 'page' ? '0.8' : '0.7',
                'changefreq' => get_post_type($postId) === 'page' ? 'monthly' : 'weekly',
                'type' => get_post_type($postId),
            ];
        }

        wp_reset_postdata();

        $sitemap = new Sitemap($entries);

        // Set headers
        status_header(200);
        header('Content-Type: ' . $sitemap->getContentType() . '; charset=UTF-8');
        header('Cache-Control: ' . $sitemap->getCacheControl());

        $lastMod = $sitemap->getLastModified();
        if ($lastMod !== null) {
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', strtotime($lastMod)) . ' GMT');
        }

        echo $sitemap->toXml();
        exit;
    }
}
