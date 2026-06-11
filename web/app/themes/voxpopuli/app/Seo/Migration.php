<?php

namespace App\Seo;

use WP_Query;

/**
 * WP-CLI command that migrates Yoast SEO postmeta to _voxpopuli_* keys.
 *
 * Usage: wp voxpopuli migrate-seo
 *
 * Maps _yoast_wpseo_* postmeta to _voxpopuli_* keys with
 * title variable expansion (%%title%%, %%sep%%, %%sitename%%, etc.).
 */
class Migration
{
    /**
     * Map of Yoast meta keys to voxpopuli meta keys.
     *
     * @var array<string, string>
     */
    public const META_MAP = [
        '_yoast_wpseo_metadesc' => '_voxpopuli_meta_desc',
        '_yoast_wpseo_opengraph-title' => '_voxpopuli_og_title',
        '_yoast_wpseo_opengraph-description' => '_voxpopuli_og_desc',
        '_yoast_wpseo_opengraph-image' => '_voxpopuli_og_image',
        '_yoast_wpseo_canonical' => '_voxpopuli_canonical',
    ];

    /**
     * Yoast meta keys that support title variable expansion.
     *
     * @var array<string>
     */
    public const EXPANDABLE_KEYS = [
        '_yoast_wpseo_metadesc',
        '_yoast_wpseo_opengraph-title',
        '_yoast_wpseo_opengraph-description',
    ];

    /**
     * Map Yoast _yoast_wpseo_* meta to _voxpopuli_* meta.
     *
     * This is a pure function for testability. The actual WP-CLI handler
     * will call this method with data fetched from WordPress.
     *
     * @param  array<string, string>  $yoastMeta  Yoast meta key-value pairs
     * @param  array<string, mixed>  $context  Expansion context (title, sep, sitename, etc.)
     * @param  array<string, string>  $existing  Existing _voxpopuli_* values (to skip)
     * @return array<string, string>  Mapped voxpopuli meta key-value pairs
     */
    public static function mapYoastMeta(
        array $yoastMeta,
        array $context = [],
        array $existing = [],
    ): array {
        $mapped = [];

        foreach (self::META_MAP as $yoastKey => $voxpopuliKey) {
            if (! isset($yoastMeta[$yoastKey]) || $yoastMeta[$yoastKey] === '') {
                continue;
            }

            $value = $yoastMeta[$yoastKey];

            // Apply title variable expansion for supported keys
            if (in_array($yoastKey, self::EXPANDABLE_KEYS, true)) {
                $value = TitleExpander::expand($value, $context);
            }

            // Do not overwrite existing values — include existing in result
            if (isset($existing[$voxpopuliKey]) && $existing[$voxpopuliKey] !== '') {
                $mapped[$voxpopuliKey] = $existing[$voxpopuliKey];
                continue;
            }

            $mapped[$voxpopuliKey] = $value;
        }

        // Handle robots → noindex mapping separately
        if (isset($yoastMeta['_yoast_wpseo_robots'])) {
            $robots = strtolower($yoastMeta['_yoast_wpseo_robots']);
            $noindex = str_contains($robots, 'noindex') ? '1' : '0';

            if (! isset($existing['_voxpopuli_noindex']) || $existing['_voxpopuli_noindex'] === '') {
                $mapped['_voxpopuli_noindex'] = $noindex;
            }
        }

        return $mapped;
    }

    /**
     * WP-CLI command handler for `wp voxpopuli migrate-seo`.
     *
     * Iterates over all published posts and pages, reads Yoast meta,
     * and writes _voxpopuli_* keys.
     *
     * ## OPTIONS
     *
     * [--dry-run]
     * : Preview changes without writing to database.
     *
     * [--post-type=<post-type>]
     * : Limit migration to specific post type(s). Default: post,page.
     *
     * @param  array<string>  $args
     * @param  array<string, string>  $assocArgs
     */
    public static function handle(array $args, array $assocArgs): void
    {
        $dryRun = isset($assocArgs['dry-run']);
        $postTypes = isset($assocArgs['post-type'])
            ? explode(',', $assocArgs['post-type'])
            : ['post', 'page'];

        $query = new WP_Query([
            'post_type' => $postTypes,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'no_found_rows' => true,
        ]);

        $total = $query->post_count;
        $migrated = 0;
        $skipped = 0;

        \WP_CLI::log("Found {$total} published posts/pages to process.");

        $postIds = $query->posts;
        $chunks = array_chunk($postIds, 500);

        foreach ($chunks as $chunk) {
            // Prime caches for posts and post meta for this chunk to avoid N+1 queries.
            if (function_exists('_prime_post_caches')) {
                _prime_post_caches($chunk, false, true);
            }

            foreach ($chunk as $postId) {
                $yoastMeta = [];
                $yoastKeys = [
                    '_yoast_wpseo_metadesc',
                    '_yoast_wpseo_opengraph-title',
                    '_yoast_wpseo_opengraph-description',
                    '_yoast_wpseo_opengraph-image',
                    '_yoast_wpseo_canonical',
                    '_yoast_wpseo_robots',
                ];

                foreach ($yoastKeys as $key) {
                    $value = get_post_meta($postId, $key, true);
                    if ($value !== '' && $value !== false) {
                        $yoastMeta[$key] = $value;
                    }
                }

                if (empty($yoastMeta)) {
                    $skipped++;
                    continue;
                }

                // Build expansion context
                $context = [
                    'title' => get_the_title($postId),
                    'sep' => '-',
                    'sitename' => get_bloginfo('name'),
                    'excerpt' => get_the_excerpt($postId),
                ];

                // Read existing _voxpopuli_* values to avoid overwriting
                $existing = [];
                foreach (self::META_MAP as $voxpopuliKey) {
                    $val = get_post_meta($postId, $voxpopuliKey, true);
                    if ($val !== '' && $val !== false) {
                        $existing[$voxpopuliKey] = $val;
                    }
                }
                // Also check noindex
                $noindexVal = get_post_meta($postId, '_voxpopuli_noindex', true);
                if ($noindexVal !== '' && $noindexVal !== false) {
                    $existing['_voxpopuli_noindex'] = $noindexVal;
                }

                $mapped = self::mapYoastMeta($yoastMeta, $context, $existing);

                if (empty($mapped)) {
                    $skipped++;
                    continue;
                }

                if ($dryRun) {
                    \WP_CLI::log("[DRY-RUN] Post {$postId}: would write " . implode(', ', array_keys($mapped)));
                } else {
                    foreach ($mapped as $key => $value) {
                        update_post_meta($postId, $key, $value);
                    }
                    \WP_CLI::log("Post {$postId}: migrated " . implode(', ', array_keys($mapped)));
                }

                $migrated++;
            }
        }

        \WP_CLI::success("Done. {$migrated} posts migrated, {$skipped} skipped (no Yoast data or already migrated).");
    }
}
