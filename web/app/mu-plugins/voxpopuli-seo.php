<?php
/**
 * Plugin Name:  VoxPopuli SEO
 * Description:  SEO meta tags, Open Graph, Twitter Cards, JSON-LD structured data, 
 *               sitemap, and performance optimizations for VoxPopuli Digital.
 * Version:      1.0.0
 * Author:       VoxPopuli Digital
 * License:      MIT
 */

// ──────────────────────────────────────────────
// 1. DISABLE jQuery Migrate
// ──────────────────────────────────────────────
add_filter('wp_default_scripts', function ($scripts) {
    if (! empty($scripts->registered['jquery'])) {
        $scripts->registered['jquery']->deps = array_diff(
            $scripts->registered['jquery']->deps,
            ['jquery-migrate']
        );
    }
});

// ──────────────────────────────────────────────
// 2. DISABLE WordPress Core Sitemaps (custom one below)
// ──────────────────────────────────────────────
add_filter('wp_sitemaps_enabled', '__return_false');

// ──────────────────────────────────────────────
// 3. RENDER SEO META TAGS (OG, Twitter, Description, Canonical, Robots)
// ──────────────────────────────────────────────
add_action('wp_head', function () {
    $meta = [];

    // --- Defaults ---
    $site_name   = get_bloginfo('name');
    $site_desc   = get_bloginfo('description');
    $home_url    = home_url('/');
    $is_front    = is_front_page() || is_home();

    // --- Resolve per-page data ---
    $meta_title       = $site_name;
    $meta_desc        = $site_desc;
    $og_title         = $site_name;
    $og_desc          = $site_desc;
    $og_image         = '';
    $canonical_url    = '';
    $robots_noindex   = false;

    if (is_singular()) {
        $post_id       = get_queried_object_id();
        $meta_title    = get_the_title($post_id) ?: $site_name;
        $canonical_url = get_permalink($post_id);

        // Custom meta desc from post meta
        $custom_desc = get_post_meta($post_id, '_voxpopuli_meta_desc', true);

        // For front page, use site description instead of page content
        if (is_front_page()) {
            $meta_desc = $custom_desc ?: $site_desc;
            $meta_title = $site_name;
            $canonical_url = $home_url;
        } else {
            $meta_desc = $custom_desc ?: wp_trim_words(strip_shortcodes(get_post_field('post_excerpt', $post_id) ?: get_the_content(null, false, $post_id)), 25);
        }

        // OG fields from post meta or fallback
        $og_title    = get_post_meta($post_id, '_voxpopuli_og_title', true);
        $og_desc     = get_post_meta($post_id, '_voxpopuli_og_desc', true);
        $robots_noindex = get_post_meta($post_id, '_voxpopuli_noindex', true) === '1';

        if (is_front_page()) {
            $og_title = $og_title ?: $site_name;
            $og_desc  = $og_desc ?: $site_desc;
        } else {
            $og_title = $og_title ?: $meta_title;
            $og_desc  = $og_desc ?: $meta_desc;
        }

        // OG Image: custom > featured image > site logo
        $og_image_id = get_post_meta($post_id, '_voxpopuli_og_image', true);
        if (empty($og_image_id)) {
            $og_image_id = get_post_thumbnail_id($post_id);
        }
        if ($og_image_id) {
            $og_image = wp_get_attachment_url((int) $og_image_id);
        }

        // Canonical from post meta
        $custom_canonical = get_post_meta($post_id, '_voxpopuli_canonical', true);
        if ($custom_canonical) {
            $canonical_url = $custom_canonical;
        }
    } elseif ($is_front) {
        $canonical_url = $home_url;
    } elseif (is_category() || is_tag() || is_tax()) {
        $canonical_url   = get_term_link(get_queried_object());
        $meta_title      = single_term_title('', false) . ' – ' . $site_name;
        $term_desc       = term_description();
        $meta_desc       = $term_desc ? wp_trim_words(strip_tags($term_desc), 25) : $site_desc;
        $og_title        = single_term_title('', false);
        $og_desc         = $meta_desc;
    } elseif (is_search()) {
        $meta_title = sprintf('Resultados de búsqueda: %s – %s', get_search_query(), $site_name);
        $meta_desc  = sprintf('Resultados de búsqueda para "%s" en %s.', get_search_query(), $site_name);
    } elseif (is_author()) {
        $author    = get_queried_object();
        $meta_title = sprintf('%s – %s', $author->display_name, $site_name);
        $meta_desc  = sprintf('Artículos de %s en %s.', $author->display_name, $site_name);
    }

    // --- Truncate ---
    if (mb_strlen($meta_desc) > 160) {
        $meta_desc = mb_substr($meta_desc, 0, 157) . '...';
    }

    // --- Robots ---
    if (get_option('blog_public') !== '1') {
        $meta[] = '<meta name="robots" content="noindex, nofollow">';
    } elseif ($robots_noindex) {
        $meta[] = '<meta name="robots" content="noindex">';
    }

    // --- Meta Description ---
    if ($meta_desc) {
        $meta[] = '<meta name="description" content="' . esc_attr($meta_desc) . '">';
    }

    // --- Canonical ---
    if ($canonical_url) {
        $meta[] = '<link rel="canonical" href="' . esc_url($canonical_url) . '">';
    }

    // --- Open Graph ---
    $meta[] = '<meta property="og:site_name" content="' . esc_attr($site_name) . '">';
    $meta[] = '<meta property="og:locale" content="' . esc_attr(get_locale()) . '">';
    $meta[] = '<meta property="og:type" content="' . ($is_front ? 'website' : 'article') . '">';
    $meta[] = '<meta property="og:url" content="' . esc_url($canonical_url ?: $home_url) . '">';

    if ($og_title) {
        $meta[] = '<meta property="og:title" content="' . esc_attr($og_title) . '">';
    }
    if ($og_desc) {
        $meta[] = '<meta property="og:description" content="' . esc_attr($og_desc) . '">';
    }
    if ($og_image) {
        $meta[] = '<meta property="og:image" content="' . esc_url($og_image) . '">';
        $meta[] = '<meta property="og:image:width" content="1200">';
        $meta[] = '<meta property="og:image:height" content="630">';
    }

    // --- Twitter Cards ---
    $meta[] = '<meta name="twitter:card" content="summary_large_image">';
    if ($og_title) {
        $meta[] = '<meta name="twitter:title" content="' . esc_attr($og_title) . '">';
    }
    if ($og_desc) {
        $meta[] = '<meta name="twitter:description" content="' . esc_attr($og_desc) . '">';
    }
    if ($og_image) {
        $meta[] = '<meta name="twitter:image" content="' . esc_url($og_image) . '">';
    }

    echo "\t<!-- VoxPopuli SEO -->\n";
    foreach ($meta as $tag) {
        echo "\t" . $tag . "\n";
    }
    echo "\t<!-- /VoxPopuli SEO -->\n";
}, 1);

// ──────────────────────────────────────────────
// 4. JSON-LD STRUCTURED DATA
// ──────────────────────────────────────────────
add_action('wp_head', function () {
    $site_name = get_bloginfo('name');
    $site_url  = home_url('/');

    $graph = [];

    // Organization
    $graph[] = [
        '@type' => 'Organization',
        'name'  => $site_name,
        'url'   => $site_url,
        'logo'  => [
            '@type' => 'ImageObject',
            'url'   => get_theme_mod('custom_logo')
                ? wp_get_attachment_url(get_theme_mod('custom_logo'))
                : '',
        ],
        'sameAs' => [
            'https://x.com/voxpopulidigital',
            'https://instagram.com/voxpopulidigital',
        ],
    ];

    // WebSite
    $graph[] = [
        '@type'       => 'WebSite',
        'name'        => $site_name,
        'url'         => $site_url,
        'potentialAction' => [
            '@type'       => 'SearchAction',
            'target'      => $site_url . '?s={search_term_string}',
            'query-input' => 'required name=search_term_string',
        ],
    ];

    // Article (single posts only)
    if (is_singular('post')) {
        $post_id     = get_queried_object_id();
        $author_name = get_the_author_meta('display_name', get_post_field('post_author', $post_id));
        $categories  = wp_get_post_categories($post_id, ['fields' => 'names']);

        $article = [
            '@type'         => 'Article',
            '@id'           => get_permalink($post_id) . '#article',
            'headline'      => get_the_title($post_id),
            'url'           => get_permalink($post_id),
            'datePublished' => get_the_date('c', $post_id),
            'dateModified'  => get_the_modified_date('c', $post_id),
            'author'        => [
                '@type' => 'Person',
                'name'  => $author_name ?: $site_name,
            ],
            'publisher'     => [
                '@type' => 'Organization',
                'name'  => $site_name,
            ],
        ];

        if (! empty($categories)) {
            $article['articleSection'] = implode(', ', $categories);
        }

        $image_id = get_post_thumbnail_id($post_id);
        if ($image_id) {
            $article['image'] = [
                '@type' => 'ImageObject',
                'url'   => wp_get_attachment_url($image_id),
            ];
        }

        $graph[] = $article;
    }

    // BreadcrumbList
    $breadcrumbs = [];
    $breadcrumbs[] = [
        '@type' => 'ListItem',
        'position' => 1,
        'name'  => 'Inicio',
        'item'  => $site_url,
    ];

    if (is_singular()) {
        $cats = get_the_category(get_queried_object_id());
        if (! empty($cats)) {
            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => 2,
                'name'  => $cats[0]->name,
                'item'  => get_category_link($cats[0]->term_id),
            ];
        }
        $breadcrumbs[] = [
            '@type' => 'ListItem',
            'position' => count($breadcrumbs) + 1,
            'name'  => get_the_title(get_queried_object_id()),
            'item'  => get_permalink(get_queried_object_id()),
        ];
    } elseif (is_category()) {
        $breadcrumbs[] = [
            '@type' => 'ListItem',
            'position' => 2,
            'name'  => single_term_title('', false),
            'item'  => get_term_link(get_queried_object()),
        ];
    }

    $graph[] = [
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $breadcrumbs,
    ];

    $json = [
        '@context' => 'https://schema.org',
        '@graph'   => $graph,
    ];

    echo "\t<script type=\"application/ld+json\">\n";
    echo "\t" . wp_json_encode($json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n";
    echo "\t</script>\n";
}, 2);

// ──────────────────────────────────────────────
// 5. CUSTOM SITEMAP
// ──────────────────────────────────────────────
add_action('init', function () {
    add_rewrite_rule('^sitemap\.xml$', 'index.php?vox_sitemap=1', 'top');
    add_rewrite_tag('%vox_sitemap%', '1');
});

add_action('template_redirect', function () {
    $is_vox_sitemap = get_query_var('vox_sitemap') === '1';
    
    if (!$is_vox_sitemap) {
        return;
    }

    $query = new WP_Query([
        'post_type'      => ['post', 'page'],
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'modified',
        'order'          => 'DESC',
        'no_found_rows'  => true,
    ]);

    $entries = [];
    while ($query->have_posts()) {
        $query->the_post();
        $post_id = get_the_ID();

        if (get_post_meta($post_id, '_voxpopuli_noindex', true) === '1') {
            continue;
        }

        $entries[] = [
            'loc'        => get_permalink($post_id),
            'lastmod'    => get_the_modified_date('Y-m-d', $post_id),
            'priority'   => get_post_type($post_id) === 'page' ? '0.8' : '0.7',
            'changefreq' => get_post_type($post_id) === 'page' ? 'monthly' : 'weekly',
        ];
    }
    wp_reset_postdata();

    header('Content-Type: application/xml; charset=UTF-8');
    header('Cache-Control: public, max-age=3600');
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach ($entries as $entry) {
        echo "\t<url>\n";
        echo "\t\t<loc>" . esc_url($entry['loc']) . "</loc>\n";
        echo "\t\t<lastmod>" . esc_xml($entry['lastmod']) . "</lastmod>\n";
        echo "\t\t<changefreq>" . esc_xml($entry['changefreq']) . "</changefreq>\n";
        echo "\t\t<priority>" . esc_xml($entry['priority']) . "</priority>\n";
        echo "\t</url>\n";
    }
    echo '</urlset>' . "\n";
    exit;
}, 5);
