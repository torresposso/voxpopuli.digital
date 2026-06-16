<?php

use function App\Filters\is_allowed_dev_host;
use function App\Filters\rewrite_url_to_current_host;

// Mock necessary WordPress functions
if (!function_exists('add_filter')) {
    function add_filter() {}
}
if (!function_exists('add_action')) {
    function add_action() {}
}
if (!function_exists('wp_get_environment_type')) {
    function wp_get_environment_type()
    {
        global $mock_env_type;
        return $mock_env_type ?? 'production';
    }
}
if (!function_exists('is_ssl')) {
    function is_ssl()
    {
        global $mock_is_ssl;
        return $mock_is_ssl ?? false;
    }
}

require_once __DIR__ . '/../../web/app/themes/voxpopuli/app/filters.php';

it('allows localhost', function () {
    expect(is_allowed_dev_host('localhost'))->toBeTrue();
    expect(is_allowed_dev_host('localhost:8000'))->toBeTrue();
    expect(is_allowed_dev_host('localhost:8080'))->toBeTrue();
});

it('allows 127.0.0.1', function () {
    expect(is_allowed_dev_host('127.0.0.1'))->toBeTrue();
    expect(is_allowed_dev_host('127.0.0.1:8000'))->toBeTrue();
});

it('allows 192.168.x.x LAN IPs', function () {
    expect(is_allowed_dev_host('192.168.1.1'))->toBeTrue();
    expect(is_allowed_dev_host('192.168.0.100'))->toBeTrue();
    expect(is_allowed_dev_host('192.168.255.255'))->toBeTrue();
    expect(is_allowed_dev_host('192.168.1.1:8000'))->toBeTrue();
});

it('allows 10.x.x.x LAN IPs', function () {
    expect(is_allowed_dev_host('10.0.0.1'))->toBeTrue();
    expect(is_allowed_dev_host('10.255.255.255'))->toBeTrue();
    expect(is_allowed_dev_host('10.1.2.3'))->toBeTrue();
    expect(is_allowed_dev_host('10.0.0.1:8000'))->toBeTrue();
});

it('rejects external domains', function () {
    expect(is_allowed_dev_host('example.com'))->toBeFalse();
    expect(is_allowed_dev_host('google.com'))->toBeFalse();
    expect(is_allowed_dev_host('my-production-site.com'))->toBeFalse();
});

it('rejects other public IPs', function () {
    expect(is_allowed_dev_host('8.8.8.8'))->toBeFalse();
    expect(is_allowed_dev_host('1.1.1.1'))->toBeFalse();
    expect(is_allowed_dev_host('172.16.0.1'))->toBeFalse(); // Although private, this class B is not explicitly allowed in the regex
});

it('rejects malformed hosts', function () {
    expect(is_allowed_dev_host(''))->toBeFalse();
    expect(is_allowed_dev_host('localhost '))->toBeFalse(); // Trailing space
    expect(is_allowed_dev_host('192.168.1'))->toBeFalse(); // Incomplete IP
});


it('does not rewrite URLs if the environment is not local or development', function () {
    global $mock_env_type;
    $mock_env_type = 'staging';
    $_SERVER['HTTP_HOST'] = 'localhost:8000';
    $original_url = 'https://production.com/image.jpg';

    expect(rewrite_url_to_current_host($original_url))->toBe($original_url);
});

it('does not rewrite URLs if HTTP_HOST is not set or not an allowed dev host', function () {
    global $mock_env_type;
    $mock_env_type = 'local';
    $original_url = 'https://production.com/image.jpg';

    unset($_SERVER['HTTP_HOST']);
    expect(rewrite_url_to_current_host($original_url))->toBe($original_url);

    $_SERVER['HTTP_HOST'] = 'evil.com';
    expect(rewrite_url_to_current_host($original_url))->toBe($original_url);
});

it('does not rewrite URLs if the URL has no host (relative URLs)', function () {
    global $mock_env_type;
    $mock_env_type = 'local';
    $_SERVER['HTTP_HOST'] = 'localhost:8000';

    $relative_url = '/wp-content/uploads/image.jpg';
    expect(rewrite_url_to_current_host($relative_url))->toBe($relative_url);
});

it('does not rewrite URLs if the URL host already matches HTTP_HOST', function () {
    global $mock_env_type;
    $mock_env_type = 'local';
    $_SERVER['HTTP_HOST'] = 'localhost:8000';

    $url = 'http://localhost:8000/wp-content/uploads/image.jpg';
    expect(rewrite_url_to_current_host($url))->toBe($url);
});

it('successfully rewrites the URL to match the current HTTP_HOST and scheme', function () {
    global $mock_env_type, $mock_is_ssl;
    $mock_env_type = 'development';
    $mock_is_ssl = false;
    $_SERVER['HTTP_HOST'] = '192.168.1.5:8080';

    $url = 'https://production.com/wp-content/uploads/image.jpg';
    expect(rewrite_url_to_current_host($url))->toBe('http://192.168.1.5:8080/wp-content/uploads/image.jpg');

    $mock_is_ssl = true;
    expect(rewrite_url_to_current_host($url))->toBe('https://192.168.1.5:8080/wp-content/uploads/image.jpg');
});

it('preserves the path and query parameters of the original URL', function () {
    global $mock_env_type, $mock_is_ssl;
    $mock_env_type = 'local';
    $mock_is_ssl = false;
    $_SERVER['HTTP_HOST'] = 'localhost:8000';

    $url = 'https://production.com/search?q=test&page=2';
    expect(rewrite_url_to_current_host($url))->toBe('http://localhost:8000/search?q=test&page=2');

    $url_no_path = 'https://production.com?q=test';
    expect(rewrite_url_to_current_host($url_no_path))->toBe('http://localhost:8000?q=test');
});
