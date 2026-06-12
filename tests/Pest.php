<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
*/

expect()->extend('toStartWith', function (string $expected) {
    return $this->startsWith($expected);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
*/

if (! function_exists('app')) {
    /**
     * Minimal helper for SeoServiceProvider tests.
     */
    function app(?string $abstract = null, array $parameters = []): mixed
    {
        return $abstract ? null : new stdClass();
    }
}

if (! function_exists('get_bloginfo')) {
    /**
     * Stub for get_bloginfo.
     */
    function get_bloginfo(string $show = ''): string
    {
        return $show === 'name' ? 'Vox Populi' : '';
    }
}

if (! function_exists('get_locale')) {
    /**
     * Stub for get_locale.
     */
    function get_locale(): string
    {
        return 'es_ES';
    }
}

if (! function_exists('get_transient')) {
    function get_transient(string $transient) { return false; }
}
if (! function_exists('set_transient')) {
    function set_transient(string $transient, mixed $value, int $expiration = 0): bool { return true; }
}
if (! function_exists('get_posts')) {
    function get_posts(?array $args = null) { return []; }
}
if (! function_exists('wp_get_post_categories')) {
    function wp_get_post_categories(int $post_id = 0, array $args = array()) { return []; }
}
if (! function_exists('wp_trim_words')) {
    function wp_trim_words($text, $num_words = 55, $more = null) { return $text; }
}
if (! function_exists('wp_strip_all_tags')) {
    function wp_strip_all_tags($text, $remove_breaks = false) { return $text; }
}
if (! function_exists('get_post_field')) {
    function get_post_field(string $field, $post = null, string $context = 'display') { return ''; }
}
if (! function_exists('get_the_author_meta')) {
    function get_the_author_meta(string $field, $user_id = false) { return ''; }
}
if (! function_exists('get_the_title')) {
    function get_the_title($post = 0) { return ''; }
}
if (! function_exists('get_permalink')) {
    function get_permalink($post = 0, bool $leavename = false) { return ''; }
}
if (! function_exists('get_the_post_thumbnail_url')) {
    function get_the_post_thumbnail_url($post = null, $size = 'post-thumbnail') { return ''; }
}
if (! function_exists('get_post_thumbnail_id')) {
    function get_post_thumbnail_id($post = null) { return 0; }
}
if (! function_exists('get_post_meta')) {
    function get_post_meta($post_id, string $key = '', bool $single = false) { return ''; }
}
if (! function_exists('get_the_date')) {
    function get_the_date(string $format = '', $post = null) { return ''; }
}