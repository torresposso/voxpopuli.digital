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
    function get_transient(string $transient)
    {
        return false;
    }
}
if (! function_exists('set_transient')) {
    function set_transient(string $transient, mixed $value, int $expiration = 0): bool
    {
        return true;
    }
}
if (! function_exists('get_posts')) {
    function get_posts(?array $args = null)
    {
        return [];
    }
}
if (! function_exists('wp_get_post_categories')) {
    function wp_get_post_categories(int $post_id = 0, array $args = [])
    {
        global $wp_post_categories;
        return $wp_post_categories ?? [];
    }
}
if (! function_exists('wp_trim_words')) {
    function wp_trim_words($text, $num_words = 55, $more = null)
    {
        return $text;
    }
}
if (! function_exists('wp_strip_all_tags')) {
    function wp_strip_all_tags($text, $remove_breaks = false)
    {
        return $text;
    }
}
if (! function_exists('get_post_field')) {
    function get_post_field(string $field, $post = null, string $context = 'display')
    {
        global $wp_post_content_map;
        return $wp_post_content_map[$post] ?? '';
    }
}
if (! function_exists('get_the_author_meta')) {
    function get_the_author_meta(string $field, $user_id = false)
    {
        return '';
    }
}
if (! function_exists('get_the_title')) {
    function get_the_title($post = 0)
    {
        global $mock_get_the_title, $wp_titles, $wp_current_post_id;
        if (isset($mock_get_the_title)) {
            return $mock_get_the_title;
        }
        $id = $post ?: ($wp_current_post_id ?? 0);
        return $wp_titles[$id] ?? '';
    }
}
if (! function_exists('get_permalink')) {
    function get_permalink($post = 0, bool $leavename = false)
    {
        global $wp_permalinks, $wp_current_post_id;
        $id = $post ?: ($wp_current_post_id ?? 0);
        return $wp_permalinks[$id] ?? '';
    }
}
if (! function_exists('get_the_post_thumbnail_url')) {
    function get_the_post_thumbnail_url($post = null, $size = 'post-thumbnail')
    {
        return '';
    }
}
if (! function_exists('get_post_thumbnail_id')) {
    function get_post_thumbnail_id($post = null)
    {
        global $wp_post_thumbnail_id;
        return $wp_post_thumbnail_id ?? 0;
    }
}
if (! function_exists('get_post_meta')) {
    function get_post_meta($post_id, string $key = '', bool $single = false)
    {
        global $wp_post_meta_map;
        return $wp_post_meta_map[$post_id][$key] ?? '';
    }
}
if (! function_exists('update_post_meta')) {
    function update_post_meta($post_id, string $key, $value, $prev_value = ''): int|bool
    {
        global $wp_post_meta_map;
        $wp_post_meta_map[$post_id][$key] = $value;
        return true;
    }
}
if (! function_exists('get_the_date')) {
    function get_the_date(string $format = '', $post = null)
    {
        global $wp_the_date;
        return $wp_the_date ?? '';
    }
}

if (! function_exists('is_home')) {
    function is_home()
    {
        global $wp_is_home;
        return $wp_is_home ?? false;
    }
}
if (! function_exists('get_option')) {
    function get_option($option, $default = false)
    {
        global $wp_options, $mock_get_option_page_comments;
        if ($option === 'page_comments' && isset($mock_get_option_page_comments)) {
            return $mock_get_option_page_comments;
        }
        return $wp_options[$option] ?? $default;
    }
}
if (! function_exists('esc_html')) {
    function esc_html(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}
if (! function_exists('__')) {
    function __($text, $domain = 'default')
    {
        return $text;
    }
}
if (! function_exists('is_archive')) {
    function is_archive()
    {
        global $wp_is_archive;
        return $wp_is_archive ?? false;
    }
}
if (! function_exists('get_the_archive_title')) {
    function get_the_archive_title()
    {
        global $wp_archive_title;
        return $wp_archive_title ?? '';
    }
}
if (! function_exists('is_search')) {
    function is_search()
    {
        global $wp_is_search;
        return $wp_is_search ?? false;
    }
}
if (! function_exists('get_search_query')) {
    function get_search_query($escaped = true)
    {
        global $wp_search_query;
        return $wp_search_query ?? '';
    }
}
if (! function_exists('is_404')) {
    function is_404()
    {
        global $wp_is_404;
        return $wp_is_404 ?? false;
    }
}
if (! function_exists('wp_link_pages')) {
    function wp_link_pages($args = '')
    {
        return '';
    }
}
if (! function_exists('wp_get_attachment_image_src')) {
    function wp_get_attachment_image_src($attachment_id, $size = 'thumbnail', $icon = false)
    {
        global $wp_attachment_image_src;
        return $wp_attachment_image_src ?? false;
    }
}
if (! function_exists('wp_get_attachment_caption')) {
    function wp_get_attachment_caption($post_id = 0)
    {
        global $wp_attachment_caption;
        return $wp_attachment_caption ?? '';
    }
}
if (! function_exists('get_the_ID')) {
    function get_the_ID()
    {
        global $wp_the_id;
        return $wp_the_id ?? 0;
    }
}
if (! function_exists('_n')) {
    function _n($single, $plural, $number, $domain = 'default')
    {
        return $number == 1 ? $single : $plural;
    }
}
if (! function_exists('get_the_category')) {
    function get_the_category($id = false)
    {
        global $wp_the_category;
        return $wp_the_category ?? [];
    }
}
if (! function_exists('get_term')) {
    function get_term($term, $taxonomy = '', $output = 'OBJECT', $filter = 'raw')
    {
        global $wp_term;
        return $wp_term ?? null;
    }
}
if (! function_exists('is_wp_error')) {
    function is_wp_error($thing)
    {
        return false;
    }
}
if (! function_exists('get_category_link')) {
    function get_category_link($category)
    {
        return 'link-to-cat-' . $category;
    }
}
if (! function_exists('wp_get_attachment_image_url')) {
    function wp_get_attachment_image_url($attachment_id, $size = 'thumbnail', $icon = false)
    {
        return '';
    }
}
if (! function_exists('get_the_excerpt')) {
    function get_the_excerpt($post = null)
    {
        global $wp_the_excerpt;
        return $wp_the_excerpt ?? '';
    }
}
if (! function_exists('wp_reset_postdata')) {
    function wp_reset_postdata() {}
}
if (! function_exists('sprintf')) {
    function sprintf($format, ...$args)
    {
        return \sprintf($format, ...$args);
    }
}


if (! class_exists('WP_Query')) {
    class WP_Query
    {
        public $posts = [];
        public $current_post = -1;
        public function __construct($args = [])
        {
            global $wp_query_mock;
            if ($wp_query_mock) {
                $this->posts = $wp_query_mock->posts;
            }
        }
        public function have_posts()
        {
            return ($this->current_post + 1) < count($this->posts);
        }
        public function the_post()
        {
            $this->current_post++;
            global $wp_titles; // So get_the_title knows what to return
            global $wp_permalinks;
            // Since get_the_title doesn't accept an argument directly inside the loop we simulate it
            // by setting global state if we needed.
            // Or we just rely on Pest.php returning what we mapped for the ID? Wait, get_the_title() is called without args.
            // Let's modify get_the_title to use current_post
            global $wp_current_post_id;
            $wp_current_post_id = $this->posts[$this->current_post] ?? 0;
        }
    }
}

if (! class_exists('WPSEO_Primary_Term')) {
    class WPSEO_Primary_Term
    {
        public function __construct($taxonomy, $post_id) {}
        public function get_primary_term()
        {
            global $wp_yoast_primary_term;
            return $wp_yoast_primary_term;
        }
    }
}
