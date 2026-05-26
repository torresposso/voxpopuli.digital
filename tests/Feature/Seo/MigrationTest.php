<?php

use App\Seo\Migration;

it('maps Yoast meta description to voxpopuli meta desc', function () {
    $yoastMeta = [
        '_yoast_wpseo_metadesc' => 'Yoast meta description',
    ];

    $mapped = Migration::mapYoastMeta($yoastMeta);

    expect($mapped['_voxpopuli_meta_desc'])->toBe('Yoast meta description');
});

it('maps Yoast OG title to voxpopuli OG title', function () {
    $yoastMeta = [
        '_yoast_wpseo_opengraph-title' => 'Yoast OG Title',
    ];

    $mapped = Migration::mapYoastMeta($yoastMeta);

    expect($mapped['_voxpopuli_og_title'])->toBe('Yoast OG Title');
});

it('maps Yoast OG description to voxpopuli OG desc', function () {
    $yoastMeta = [
        '_yoast_wpseo_opengraph-description' => 'Yoast OG Desc',
    ];

    $mapped = Migration::mapYoastMeta($yoastMeta);

    expect($mapped['_voxpopuli_og_desc'])->toBe('Yoast OG Desc');
});

it('maps Yoast OG image to voxpopuli OG image', function () {
    $yoastMeta = [
        '_yoast_wpseo_opengraph-image' => '123',
    ];

    $mapped = Migration::mapYoastMeta($yoastMeta);

    expect($mapped['_voxpopuli_og_image'])->toBe('123');
});

it('maps Yoast canonical to voxpopuli canonical', function () {
    $yoastMeta = [
        '_yoast_wpseo_canonical' => 'https://example.com/canonical',
    ];

    $mapped = Migration::mapYoastMeta($yoastMeta);

    expect($mapped['_voxpopuli_canonical'])->toBe('https://example.com/canonical');
});

it('maps Yoast robots noindex to voxpopuli noindex 1', function () {
    $yoastMeta = [
        '_yoast_wpseo_robots' => 'noindex',
    ];

    $mapped = Migration::mapYoastMeta($yoastMeta);

    expect($mapped['_voxpopuli_noindex'])->toBe('1');
});

it('maps Yoast robots without noindex to voxpopuli noindex 0', function () {
    $yoastMeta = [
        '_yoast_wpseo_robots' => 'index',
    ];

    $mapped = Migration::mapYoastMeta($yoastMeta);

    expect($mapped['_voxpopuli_noindex'])->toBe('0');
});

it('maps Yoast robots with complex value containing noindex', function () {
    $yoastMeta = [
        '_yoast_wpseo_robots' => 'max-snippet:-1, max-image-preview:large, noindex',
    ];

    $mapped = Migration::mapYoastMeta($yoastMeta);

    expect($mapped['_voxpopuli_noindex'])->toBe('1');
});

it('returns empty array for empty Yoast meta input', function () {
    $mapped = Migration::mapYoastMeta([]);

    expect($mapped)->toBe([]);
});

it('skips unknown Yoast keys', function () {
    $yoastMeta = [
        '_yoast_wpseo_unknown_key' => 'some value',
        '_yoast_wpseo_metadesc' => 'desc',
    ];

    $mapped = Migration::mapYoastMeta($yoastMeta);

    expect($mapped)->toHaveKey('_voxpopuli_meta_desc');
    expect($mapped)->not->toHaveKey('_voxpopuli_unknown_key');
});

it('maps all 6 Yoast keys at once', function () {
    $yoastMeta = [
        '_yoast_wpseo_metadesc' => 'desc',
        '_yoast_wpseo_opengraph-title' => 'og-title',
        '_yoast_wpseo_opengraph-description' => 'og-desc',
        '_yoast_wpseo_opengraph-image' => '42',
        '_yoast_wpseo_canonical' => 'https://example.com',
        '_yoast_wpseo_robots' => 'noindex',
    ];

    $mapped = Migration::mapYoastMeta($yoastMeta);

    expect($mapped)->toHaveKey('_voxpopuli_meta_desc');
    expect($mapped)->toHaveKey('_voxpopuli_og_title');
    expect($mapped)->toHaveKey('_voxpopuli_og_desc');
    expect($mapped)->toHaveKey('_voxpopuli_og_image');
    expect($mapped)->toHaveKey('_voxpopuli_canonical');
    expect($mapped)->toHaveKey('_voxpopuli_noindex');
    expect($mapped['_voxpopuli_meta_desc'])->toBe('desc');
    expect($mapped['_voxpopuli_og_title'])->toBe('og-title');
    expect($mapped['_voxpopuli_og_desc'])->toBe('og-desc');
    expect($mapped['_voxpopuli_og_image'])->toBe('42');
    expect($mapped['_voxpopuli_canonical'])->toBe('https://example.com');
    expect($mapped['_voxpopuli_noindex'])->toBe('1');
});

it('expands Yoast title variables during mapping', function () {
    $yoastMeta = [
        '_yoast_wpseo_opengraph-title' => '%%title%% %%sep%% %%sitename%%',
    ];
    $context = [
        'title' => 'Actual Post Title',
        'sep' => '-',
        'sitename' => 'My Site',
    ];

    $mapped = Migration::mapYoastMeta($yoastMeta, $context);

    expect($mapped['_voxpopuli_og_title'])->toBe('Actual Post Title - My Site');
});

it('expands title variables in meta description', function () {
    $yoastMeta = [
        '_yoast_wpseo_metadesc' => '%%excerpt%%',
    ];
    $context = [
        'excerpt' => 'Post excerpt here',
    ];

    $mapped = Migration::mapYoastMeta($yoastMeta, $context);

    expect($mapped['_voxpopuli_meta_desc'])->toBe('Post excerpt here');
});

it('preserves existing voxpopuli meta keys when skipExisting is true', function () {
    $yoastMeta = [
        '_yoast_wpseo_metadesc' => 'new value',
    ];
    $existing = [
        '_voxpopuli_meta_desc' => 'existing value',
    ];

    $mapped = Migration::mapYoastMeta($yoastMeta, [], $existing);

    expect($mapped)->toHaveKey('_voxpopuli_meta_desc');
});

it('returns empty array when no Yoast keys are present', function () {
    $yoastMeta = [
        'some_other_key' => 'value',
    ];

    $mapped = Migration::mapYoastMeta($yoastMeta);

    expect($mapped)->toBe([]);
});
