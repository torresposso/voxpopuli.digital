<?php

use function App\is_allowed_dev_host;

// Mock necessary WordPress functions
if (!function_exists('add_filter')) {
    function add_filter() {}
}
if (!function_exists('add_action')) {
    function add_action() {}
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
