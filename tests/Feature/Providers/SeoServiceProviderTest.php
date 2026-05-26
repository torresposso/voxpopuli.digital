<?php

use App\Providers\SeoServiceProvider;

it('registers the service provider', function () {
    $provider = new SeoServiceProvider(app());

    expect($provider)->toBeInstanceOf(SeoServiceProvider::class);
});

it('has a boot method that registers wp_head action', function () {
    $provider = new SeoServiceProvider(app());

    expect(method_exists($provider, 'boot'))->toBeTrue();
});

it('has a register method', function () {
    $provider = new SeoServiceProvider(app());

    expect(method_exists($provider, 'register'))->toBeTrue();
});
