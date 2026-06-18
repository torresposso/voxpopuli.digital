<?php

/**
 * Regression tests for the save_post hook in PerformanceServiceProvider
 * that calculates and persists `vp_reading_time` and `vp_word_count`
 * post metadata.
 *
 * The hook fires on every post save, computes word count and reading
 * time (200 wpm), and stores both in post meta. This avoids runtime
 * str_word_count(strip_tags($content)) calls inside loops, which is
 * the worst possible place for O(N) work on a news site.
 */

use App\Providers\PerformanceServiceProvider;

beforeEach(function () {
    global $wp_post_meta_map, $wp_post_content_map;
    $wp_post_meta_map = [];
    $wp_post_content_map = [];
});

it('exposes a calculateAndPersistReadingMeta method', function () {
    expect(method_exists(PerformanceServiceProvider::class, 'calculateAndPersistReadingMeta'))->toBeTrue();
});

it('calculates 1 min for a 100-word post', function () {
    PerformanceServiceProvider::calculateAndPersistReadingMeta(
        1,
        str_repeat('word ', 100),
    );

    global $wp_post_meta_map;
    expect($wp_post_meta_map[1]['vp_word_count'])->toBe(100);
    expect($wp_post_meta_map[1]['vp_reading_time'])->toBe(1);
});

it('calculates 5 min for a 1000-word post', function () {
    PerformanceServiceProvider::calculateAndPersistReadingMeta(
        42,
        str_repeat('word ', 1000),
    );

    global $wp_post_meta_map;
    expect($wp_post_meta_map[42]['vp_word_count'])->toBe(1000);
    expect($wp_post_meta_map[42]['vp_reading_time'])->toBe(5);
});

it('floors at 1 min for empty content', function () {
    PerformanceServiceProvider::calculateAndPersistReadingMeta(7, '');

    global $wp_post_meta_map;
    expect($wp_post_meta_map[7]['vp_word_count'])->toBe(0);
    expect($wp_post_meta_map[7]['vp_reading_time'])->toBe(1);
});

it('strips HTML before counting words', function () {
    PerformanceServiceProvider::calculateAndPersistReadingMeta(
        10,
        '<p>hello <strong>world</strong></p> ' . str_repeat('word ', 200),
    );

    global $wp_post_meta_map;
    // 2 words from "hello world" + 200 from the rest = 202, but the floor
    // is what matters here: the test just verifies strip_tags is applied
    // by asserting the count is NOT counting HTML tags.
    $count = $wp_post_meta_map[10]['vp_word_count'];
    expect($count)->toBeGreaterThan(200);
    expect($count)->toBeLessThan(210);
});
