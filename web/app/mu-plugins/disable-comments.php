<?php
/**
 * Plugin Name: Disable Comments Globally
 * Description: Globally disable comments, pings, and trackbacks.
 * Version: 1.0.0
 * Author: Antigravity
 */

// Disable support for comments and trackbacks in post types
add_action('init', function () {
    $post_types = get_post_types();
    foreach ($post_types as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
}, 99);

// Filter comments template to load our empty file
add_filter('comments_template', function () {
    return __DIR__ . '/empty-comments.php';
});

// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove comments page in admin menu
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
    remove_submenu_page('options-general.php', 'options-discussion.php');
});

// Redirect any user trying to access comments page or discussion page directly
add_action('admin_init', function () {
    global $pagenow;
    if ($pagenow === 'edit-comments.php' || $pagenow === 'options-discussion.php') {
        wp_safe_redirect(admin_url());
        exit;
    }
});

// Remove comments links from admin bar
add_action('init', function () {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
});

// Disable REST API support for comments
add_filter('rest_endpoints', function ($endpoints) {
    if (isset($endpoints['/wp/v2/comments'])) {
        unset($endpoints['/wp/v2/comments']);
    }
    if (isset($endpoints['/wp/v2/comments/(?P<id>[\d]+)'])) {
        unset($endpoints['/wp/v2/comments/(?P<id>[\d]+)']);
    }
    return $endpoints;
});

// Translate theme-specific English strings
add_filter('gettext', function ($translated_text, $text, $domain) {
    if ($domain === 'bunyad' && $text === 'Related *Posts*') {
        return 'Artículos relacionados';
    }
    return $translated_text;
}, 20, 3);

