<?php

namespace App\Filters;

/**
 * Disable REST API user endpoints for anonymous users to prevent enumeration.
 */
add_filter('rest_authentication_errors', function ($result) {
    if (true === $result || is_wp_error($result)) {
        return $result;
    }

    $request_uri = sanitize_text_field($_SERVER['REQUEST_URI'] ?? '');
    $rest_route = sanitize_text_field($_GET['rest_route'] ?? '');

    if (! is_user_logged_in()) {
        if (str_contains($request_uri, '/wp/v2/users') || str_contains($rest_route, '/wp/v2/users')) {
            return new \WP_Error(
                'rest_cannot_access',
                __('Only authenticated users can access user endpoints.', 'voxpopuli'),
                ['status' => rest_authorization_required_code()],
            );
        }
    }

    return $result;
});

/**
 * Block author enumeration scans via query parameter (?author=N).
 */
add_action('parse_request', function ($wp) {
    if (isset($_GET['author']) && ! is_user_logged_in()) {
        wp_safe_redirect(home_url(), 301);
        exit;
    }
});
