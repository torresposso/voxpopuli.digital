<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PerformanceServiceProvider extends ServiceProvider
{
    /**
     * Whether SQLite PRAGMAs have been applied in the current request.
     * Exposed as a class property (not a method-local static) so tests
     * can reset it via {@see resetSqlitePragmasAppliedForTesting()}.
     */
    public static bool $pragmasApplied = false;

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * Apply SQLite PRAGMAs (WAL + NORMAL + busy_timeout) on every request.
         *
         * PRAGMAs are connection-level but WAL persists in the DB header and
         * synchronous persists too, so this is effectively idempotent at the
         * DB level. We still guard with a class-level flag to avoid running
         * the PRAGMA queries more than once per request even if `init`
         * fires multiple times.
         *
         * Set DB_APPLY_WAL_PRAGMA=false in .env to disable.
         */
        add_action('init', function () {
            self::applySqlitePragmas($GLOBALS['wpdb'] ?? null);
        }, 1);

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

            self::calculateAndPersistReadingMeta(
                $post_id,
                get_post_field('post_content', $post_id),
            );
        }, 10, 1);

        /**
         * Invalidate Hero and Homepage sections caches when a post is saved or deleted.
         */
        add_action('save_post', function ($post_id) {
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }
            if (wp_is_post_revision($post_id)) {
                return;
            }

            delete_transient(\App\View\Components\Hero::getCacheKey());
            (new \App\View\Composers\Index())->bustCache();
            \Illuminate\Support\Facades\Cache::forget('voxpopuli_drawer_featured_post');
        }, 10, 1);
        add_action('deleted_post', function () {
            delete_transient(\App\View\Components\Hero::getCacheKey());
            (new \App\View\Composers\Index())->bustCache();
            \Illuminate\Support\Facades\Cache::forget('voxpopuli_drawer_featured_post');
        });

        /**
         * Scale down big images uploaded by users/authors to a max of 2048px and convert to WebP.
         */
        add_filter('wp_handle_upload', function (array $upload) {
            if (empty($upload['file']) || empty($upload['type'])) {
                return $upload;
            }

            $file_path = $upload['file'];
            $mime_type = $upload['type'];

            // Only process JPEG and PNG
            if (! in_array($mime_type, ['image/jpeg', 'image/png'], true)) {
                return $upload;
            }

            // Avoid failures and keep original if editor is not available
            $editor = wp_get_image_editor($file_path);
            if (is_wp_error($editor)) {
                return $upload;
            }

            // 1. Resize if image exceeds 2048px
            $size = $editor->get_size();
            if ($size) {
                $max_dim = 2048;
                if ($size['width'] > $max_dim || $size['height'] > $max_dim) {
                    $editor->resize($max_dim, $max_dim, false);
                }
            }

            // 2. Build destination WebP path and save
            $webp_file_path = preg_replace('/\.(jpe?g|png)$/i', '.webp', $file_path);
            $saved = $editor->save($webp_file_path, 'image/webp');

            if (! is_wp_error($saved) && ! empty($saved['path'])) {
                // Delete original file to prevent duplicates
                if (file_exists($file_path)) {
                    @unlink($file_path);
                }

                // Update the upload details so WordPress registers the WebP file
                $upload['file'] = $saved['path'];
                $upload['url'] = preg_replace('/\.(jpe?g|png)$/i', '.webp', $upload['url']);
                $upload['type'] = 'image/webp';
            }

            return $upload;
        }, 10, 1);

        /**
         * Lower the big image threshold size to 2048px.
         */
        add_filter('big_image_size_threshold', fn() => 2048);
    }

    /**
     * Apply SQLite performance PRAGMAs to the active wpdb connection.
     *
     * - journal_mode = WAL    : concurrent reads during writes, no DB lock
     * - synchronous  = NORMAL : fsync only at checkpoints, not every commit (~10x faster)
     * - busy_timeout = 5000   : wait up to 5s for a lock instead of failing immediately
     *
     * These PRAGMAs are no-ops if already applied. WAL is persisted in the
     * DB header; synchronous and busy_timeout are per-connection but
     * re-applying is cheap and safe.
     *
     * Refuses to run on non-SQLite wpdb implementations.
     */
    public static function applySqlitePragmas(?object $wpdb): void
    {
        if (self::$pragmasApplied) {
            return;
        }
        if ($wpdb === null || ! isset($wpdb->dbh)) {
            return;
        }

        // Kill switch: set DB_APPLY_WAL_PRAGMA=false in .env to disable.
        if (function_exists('env') && env('DB_APPLY_WAL_PRAGMA', true) === false) {
            self::$pragmasApplied = true;

            return;
        }

        $dbh = $wpdb->dbh;
        if (! $dbh instanceof \WP_SQLite_Translator) {
            self::$pragmasApplied = true;

            return;
        }

        // Native SQLite PRAGMAs (not translated by WP_SQLite_Translator).
        // execute_sqlite_query() returns a PDOStatement that we discard.
        $dbh->execute_sqlite_query('PRAGMA journal_mode = WAL');
        $dbh->execute_sqlite_query('PRAGMA synchronous = NORMAL');
        $dbh->execute_sqlite_query('PRAGMA busy_timeout = 5000');

        self::$pragmasApplied = true;
    }

    /**
     * Calculate word count and reading time (200 wpm) for a post and
     * persist them as post meta. Extracted from the save_post hook so
     * the calculation logic is unit-testable.
     *
     * Reading time is floored at 1 minute even for empty content so
     * cards and previews always have a meaningful value to display.
     */
    public static function calculateAndPersistReadingMeta(int $post_id, string $content): void
    {
        $word_count = str_word_count(strip_tags($content));
        $reading_time = max(1, (int) ceil($word_count / 200));

        update_post_meta($post_id, 'vp_word_count', $word_count);
        update_post_meta($post_id, 'vp_reading_time', $reading_time);
    }

    /**
     * Reset the PRAGMAs-applied flag. Intended for test teardown.
     */
    public static function resetSqlitePragmasAppliedForTesting(): void
    {
        self::$pragmasApplied = false;
    }
}
