<?php

use App\Vite;

beforeEach(function () {
    $this->vite = new Vite();
    $this->tmpFile = tempnam(sys_get_temp_dir(), 'hot');
    $this->vite->useHotFile($this->tmpFile);
});

afterEach(function () {
    if (file_exists($this->tmpFile)) {
        unlink($this->tmpFile);
    }
    unset($_SERVER['HTTP_HOST']);
});

test('hotAsset replaces 0.0.0.0 with the request host', function () {
    file_put_contents($this->tmpFile, 'http://0.0.0.0:5174');
    $_SERVER['HTTP_HOST'] = 'my-local-host.test:8080';

    $url = $this->vite->asset('resources/css/app.css');

    expect($url)->toBe('http://my-local-host.test:5174/resources/css/app.css');
});

test('hotAsset returns original url if HTTP_HOST is not set', function () {
    file_put_contents($this->tmpFile, 'http://0.0.0.0:5174');
    unset($_SERVER['HTTP_HOST']);

    $url = $this->vite->asset('resources/css/app.css');

    expect($url)->toBe('http://0.0.0.0:5174/resources/css/app.css');
});

test('hotAsset returns original url if host in hot file is not 0.0.0.0', function () {
    file_put_contents($this->tmpFile, 'http://localhost:5174');
    $_SERVER['HTTP_HOST'] = 'my-local-host.test:8080';

    $url = $this->vite->asset('resources/css/app.css');

    expect($url)->toBe('http://localhost:5174/resources/css/app.css');
});

test('hotAsset preserves scheme, port, and query from original url', function () {
    file_put_contents($this->tmpFile, 'https://0.0.0.0:3000');
    $_SERVER['HTTP_HOST'] = 'my-local-host.test';

    $url = $this->vite->asset('resources/css/app.css?v=123');

    expect($url)->toBe('https://my-local-host.test:3000/resources/css/app.css?v=123');
});

test('hotAsset handles IPv4 HTTP_HOST', function () {
    file_put_contents($this->tmpFile, 'http://0.0.0.0:5174');
    $_SERVER['HTTP_HOST'] = '192.168.1.10:8000';

    $url = $this->vite->asset('resources/css/app.css');

    expect($url)->toBe('http://192.168.1.10:5174/resources/css/app.css');
});
