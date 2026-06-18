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
        add_action('save_post', function () {
            delete_transient(\App\View\Components\Hero::getCacheKey());
            (new \App\View\Composers\Index())->bustCache();
        });
        add_action('deleted_post', function () {
            delete_transient(\App\View\Components\Hero::getCacheKey());
            (new \App\View\Composers\Index())->bustCache();
        });
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
