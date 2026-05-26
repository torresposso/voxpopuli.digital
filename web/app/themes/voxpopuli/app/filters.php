<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'voxpopuli'));
});

/**
 * Disable REST API user endpoints for anonymous users to prevent enumeration.
 */
add_filter('rest_authentication_errors', function ($result) {
    if (true === $result || is_wp_error($result)) {
        return $result;
    }

    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    $rest_route = $_GET['rest_route'] ?? '';

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

/**
 * Validate that the given host is in the allowed list for dev URL rewriting.
 */
function is_allowed_dev_host(string $host): bool
{
    $allowed = [
        'localhost',
        '127.0.0.1',
    ];

    // Allow any 192.168.x.x or 10.x.x.x LAN IP
    if (preg_match('/^(192\.168\.\d{1,3}\.\d{1,3}|10\.\d{1,3}\.\d{1,3}\.\d{1,3})(:\d+)?$/', $host)) {
        return true;
    }

    // Strip port for comparison
    $hostWithoutPort = explode(':', $host)[0];
    return in_array($hostWithoutPort, $allowed, true);
}

/**
 * Dynamically adjust URLs in dev to match the request host if allowed.
 */
function rewrite_url_to_current_host(string $url): string
{
    $env = function_exists('wp_get_environment_type') ? wp_get_environment_type() : '';
    if (! in_array($env, ['local', 'development'], true)) {
        return $url;
    }

    if (! isset($_SERVER['HTTP_HOST']) || ! is_allowed_dev_host($_SERVER['HTTP_HOST'])) {
        return $url;
    }

    $parts = parse_url($url);
    if (! isset($parts['host']) || $parts['host'] === $_SERVER['HTTP_HOST']) {
        return $url;
    }

    $new_scheme = is_ssl() ? 'https' : 'http';
    $new_url = $new_scheme . '://' . $_SERVER['HTTP_HOST'];
    if (isset($parts['path'])) {
        $new_url .= $parts['path'];
    }
    if (isset($parts['query'])) {
        $new_url .= '?' . $parts['query'];
    }

    return $new_url;
}

/**
 * Dynamically adjust home_url in dev to match the request host.
 */
add_filter('home_url', fn ($url) => rewrite_url_to_current_host($url), 10, 1);

/**
 * Rewrite old image URLs in post content to match the current request host.
 * Fixes: wrong port (8080 vs 8000), wrong host (localhost vs LAN IP),
 * and old /wp-content/uploads/ path → /app/uploads/ for Bedrock.
 */
add_filter('the_content', function ($content) {
    $env = function_exists('wp_get_environment_type') ? wp_get_environment_type() : '';

    if (! in_array($env, ['local', 'development'], true)) {
        return $content;
    }

    if (! isset($_SERVER['HTTP_HOST']) || ! is_allowed_dev_host($_SERVER['HTTP_HOST'])) {
        return $content;
    }

    $current_host = $_SERVER['HTTP_HOST'];
    $scheme = is_ssl() ? 'https' : 'http';

    $old_hosts = array_filter([
        'localhost:8080',
        'localhost:8000',
        function_exists('env') ? env('OLD_PRODUCTION_HOST') : ($_ENV['OLD_PRODUCTION_HOST'] ?? null),
    ]);

    $replace_host = function ($url) use ($old_hosts, $current_host, $scheme) {
        foreach ($old_hosts as $old) {
            if (str_contains($url, $old)) {
                return str_replace($old, $current_host, str_replace('http://', $scheme . '://', $url));
            }
        }
        return $url;
    };

    $content = preg_replace_callback(
        '/https?:\/\/[^\s"\']+\.(jpg|jpeg|png|gif|webp|svg|avif)(\?[^\s"\']*)?["\'\s>]/i',
        fn ($m) => $replace_host($m[0]),
        $content
    );

    $content = str_replace('/wp-content/uploads/', '/app/uploads/', $content);

    // Fallback: If a resized/thumbnail image file does not exist on disk but its original high-res version does,
    // dynamically rewrite the URL to load the original high-res version to prevent broken images.
    $content = preg_replace_callback(
        '/https?:\/\/[^\s"\']+\/app\/uploads\/([a-zA-Z0-9_\-\/]+\-\d+x\d+\.(?:jpg|jpeg|png|gif|webp|svg|avif))/i',
        function ($m) {
            $full_url = $m[0];
            $relative_path_with_suffix = $m[1]; // e.g. 2026/05/1993199-771x1024.jpg

            if (defined('WP_CONTENT_DIR')) {
                $uploads_dir = WP_CONTENT_DIR . '/uploads';
                $file_path = $uploads_dir . '/' . $relative_path_with_suffix;

                if (! file_exists($file_path)) {
                    $original_relative_path = preg_replace('/-\d+x\d+(\.(?:jpg|jpeg|png|gif|webp|svg|avif))$/i', '$1', $relative_path_with_suffix);
                    $original_file_path = $uploads_dir . '/' . $original_relative_path;

                    if (file_exists($original_file_path)) {
                        return preg_replace('/-\d+x\d+(\.(?:jpg|jpeg|png|gif|webp|svg|avif))/i', '$1', $full_url);
                    }
                }
            }
            return $full_url;
        },
        $content
    );

    return $content;
}, 999);

/**
 * Dynamically adjust site_url in dev to match the request host.
 */
add_filter('site_url', fn ($url) => rewrite_url_to_current_host($url), 10, 1);

/**
 * Dynamically adjust attachment URLs in dev to match the request host.
 */
add_filter('wp_get_attachment_url', fn ($url) => rewrite_url_to_current_host($url), 10, 1);

/**
 * Dynamically adjust image srcset URLs in dev to match the request host.
 */
add_filter('wp_calculate_image_srcset', function ($sources) {
    foreach ($sources as &$source) {
        $source['url'] = rewrite_url_to_current_host($source['url']);
    }
    return $sources;
}, 10, 1);


