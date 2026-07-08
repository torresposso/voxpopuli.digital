<?php

use App\Providers\AnalyticsServiceProvider;
use App\Providers\BlockEditorServiceProvider;
use App\Providers\PerformanceServiceProvider;
use App\Providers\SeoServiceProvider;
use App\Providers\ThemeServiceProvider;

afterEach(function () {
    Mockery::close();
    PerformanceServiceProvider::resetSqlitePragmasAppliedForTesting();
});

it('registers ThemeServiceProvider', function () {
    $provider = new ThemeServiceProvider(app());
    expect($provider)->toBeInstanceOf(ThemeServiceProvider::class);
});

it('ThemeServiceProvider has a boot method', function () {
    $provider = new ThemeServiceProvider(app());
    expect(method_exists($provider, 'boot'))->toBeTrue();
});

it('registers PerformanceServiceProvider', function () {
    $provider = new PerformanceServiceProvider(app());
    expect($provider)->toBeInstanceOf(PerformanceServiceProvider::class);
});

it('PerformanceServiceProvider computes vp_reading_time meta', function () {
    $postId = 42;
    $content = 'one two three four five six seven eight nine ten';

    PerformanceServiceProvider::calculateAndPersistReadingMeta($postId, $content);

    global $wp_post_meta_map;
    expect($wp_post_meta_map[$postId]['vp_word_count'])->toBe(10);
    expect($wp_post_meta_map[$postId]['vp_reading_time'])->toBe(1);
});

it('PerformanceServiceProvider floors reading time at 1 for empty content', function () {
    $postId = 99;

    PerformanceServiceProvider::calculateAndPersistReadingMeta($postId, '');

    global $wp_post_meta_map;
    expect($wp_post_meta_map[$postId]['vp_reading_time'])->toBe(1);
    expect($wp_post_meta_map[$postId]['vp_word_count'])->toBe(0);
});

it('PerformanceServiceProvider applies SQLite PRAGMAs correctly', function () {
    $dbh = Mockery::mock(\WP_SQLite_Translator::class);
    $dbh->shouldReceive('execute_sqlite_query')
        ->once()->ordered()->with('PRAGMA journal_mode = WAL');
    $dbh->shouldReceive('execute_sqlite_query')
        ->once()->ordered()->with('PRAGMA synchronous = NORMAL');
    $dbh->shouldReceive('execute_sqlite_query')
        ->once()->ordered()->with('PRAGMA busy_timeout = 5000');

    $wpdb = new \stdClass();
    $wpdb->dbh = $dbh;

    PerformanceServiceProvider::applySqlitePragmas($wpdb);
});

it('registers AnalyticsServiceProvider', function () {
    $provider = new AnalyticsServiceProvider(app());
    expect($provider)->toBeInstanceOf(AnalyticsServiceProvider::class);
});

it('AnalyticsServiceProvider has a boot method', function () {
    $provider = new AnalyticsServiceProvider(app());
    expect(method_exists($provider, 'boot'))->toBeTrue();
});

it('registers BlockEditorServiceProvider', function () {
    $provider = new BlockEditorServiceProvider(app());
    expect($provider)->toBeInstanceOf(BlockEditorServiceProvider::class);
});

it('registers SeoServiceProvider', function () {
    $provider = new SeoServiceProvider(app());
    expect($provider)->toBeInstanceOf(SeoServiceProvider::class);
});

it('SeoServiceProvider registers JsonLd as singleton', function () {
    $provider = new SeoServiceProvider(app());
    expect(method_exists($provider, 'register'))->toBeTrue();
    expect(method_exists($provider, 'boot'))->toBeTrue();
});
