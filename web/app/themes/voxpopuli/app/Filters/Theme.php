<?php

namespace App\Filters;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'voxpopuli'));
});

/**
 * Dequeue Gutenberg block library styles on index, home, archive, and search views to optimize CSS delivery.
 */
add_action('wp_enqueue_scripts', function () {
    if (!is_single() && !is_page()) {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('wc-blocks-style');
    }
}, 100);
