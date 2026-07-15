<?php

use App\Seo\JsonLd;

it('builds Organization schema with all fields', function () {
    $jsonld = new JsonLd();
    $org = $jsonld->organization([
        'name' => 'Vox Populi',
        'logo' => 'https://example.com/logo.png',
        'url' => 'https://voxpopuli.digital',
        'sameAs' => [
            'https://twitter.com/voxpopuli',
            'https://facebook.com/voxpopuli',
        ],
    ]);

    expect($org)->toMatchArray([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'Vox Populi',
        'logo' => 'https://example.com/logo.png',
        'url' => 'https://voxpopuli.digital',
        'sameAs' => [
            'https://twitter.com/voxpopuli',
            'https://facebook.com/voxpopuli',
        ],
    ]);
});

it('omits sameAs from Organization when empty', function () {
    $jsonld = new JsonLd();
    $org = $jsonld->organization([
        'name' => 'Vox Populi',
        'url' => 'https://voxpopuli.digital',
    ]);

    expect($org)->not->toHaveKey('sameAs');
});

it('builds WebSite schema with SearchAction', function () {
    $jsonld = new JsonLd();
    $website = $jsonld->website([
        'name' => 'Vox Populi',
        'url' => 'https://voxpopuli.digital',
        'search_url' => 'https://voxpopuli.digital/?s={search_term_string}',
    ]);

    expect($website)->toMatchArray([
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => 'Vox Populi',
        'url' => 'https://voxpopuli.digital',
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => [
                '@type' => 'EntryPoint',
                'urlTemplate' => 'https://voxpopuli.digital/?s={search_term_string}',
            ],
            'query-input' => 'required name=search_term_string',
        ],
    ]);
});

it('builds WebSite schema without SearchAction when no search_url', function () {
    $jsonld = new JsonLd();
    $website = $jsonld->website([
        'name' => 'Vox Populi',
        'url' => 'https://voxpopuli.digital',
    ]);

    expect($website)->toHaveKey('name');
    expect($website)->toHaveKey('url');
    expect($website)->not->toHaveKey('potentialAction');
});

it('builds Article schema with all required fields', function () {
    $jsonld = new JsonLd();
    $article = $jsonld->article([
        'headline' => 'Article Title',
        'description' => 'Article description',
        'datePublished' => '2026-01-15T10:00:00+00:00',
        'dateModified' => '2026-01-16T10:00:00+00:00',
        'author' => 'John Doe',
        'image' => 'https://example.com/image.jpg',
        'url' => 'https://voxpopuli.digital/article',
    ]);

    expect($article)->toMatchArray([
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => 'Article Title',
        'description' => 'Article description',
        'datePublished' => '2026-01-15T10:00:00+00:00',
        'dateModified' => '2026-01-16T10:00:00+00:00',
        'author' => [
            '@type' => 'Person',
            'name' => 'John Doe',
        ],
        'image' => 'https://example.com/image.jpg',
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => 'https://voxpopuli.digital/article',
        ],
    ]);
});

it('builds Article schema without optional fields when missing', function () {
    $jsonld = new JsonLd();
    $article = $jsonld->article([
        'headline' => 'Minimal Article',
    ]);

    expect($article)->toMatchArray([
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => 'Minimal Article',
    ]);
    expect($article)->not->toHaveKey('description');
    expect($article)->not->toHaveKey('image');
    expect($article)->not->toHaveKey('mainEntityOfPage');
    expect($article)->not->toHaveKey('author');
    expect($article)->not->toHaveKey('datePublished');
    expect($article)->not->toHaveKey('dateModified');
});

it('builds BreadcrumbList for single item', function () {
    $jsonld = new JsonLd();
    $breadcrumbs = $jsonld->breadcrumbList([
        ['name' => 'Home', 'url' => 'https://voxpopuli.digital'],
    ]);

    expect($breadcrumbs)->toMatchArray([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Home',
                'item' => 'https://voxpopuli.digital',
            ],
        ],
    ]);
});

it('builds BreadcrumbList with multiple items', function () {
    $jsonld = new JsonLd();
    $breadcrumbs = $jsonld->breadcrumbList([
        ['name' => 'Home', 'url' => 'https://voxpopuli.digital'],
        ['name' => 'News', 'url' => 'https://voxpopuli.digital/category/news'],
        ['name' => 'Article Title', 'url' => 'https://voxpopuli.digital/article'],
    ]);

    expect($breadcrumbs)->toMatchArray([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => 'https://voxpopuli.digital'],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'News', 'item' => 'https://voxpopuli.digital/category/news'],
            ['@type' => 'ListItem', 'position' => 3, 'name' => 'Article Title', 'item' => 'https://voxpopuli.digital/article'],
        ],
    ]);
});

it('builds BreadcrumbList with empty items array', function () {
    $jsonld = new JsonLd();
    $breadcrumbs = $jsonld->breadcrumbList([]);

    expect($breadcrumbs)->toMatchArray([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [],
    ]);
});

it('renders to script tag via toScript', function () {
    $jsonld = new JsonLd();
    $schema = $jsonld->organization([
        'name' => 'Test',
        'url' => 'https://example.com',
    ]);

    $script = JsonLd::toScript($schema);

    expect($script)->toContain('<script type="application/ld+json">');
    expect($script)->toContain('</script>');
    expect($script)->toContain('"@type":"Organization"');
    expect($script)->toContain('"name":"Test"');
});
