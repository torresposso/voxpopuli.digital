<?php

use App\Seo\JsonLd;
use App\Seo\MetaRenderer;
use App\Seo\SeoMeta;

it('JsonLd renders Organization schema with correct structure', function () {
    $jsonld = new JsonLd();
    $org = $jsonld->organization([
        'name' => 'Acta Populi',
        'url' => 'https://voxpopuli.digital',
        'logo' => 'https://voxpopuli.digital/logo.png',
        'sameAs' => [
            'https://bsky.app/profile/voxpopuli',
            'https://facebook.com/voxpopuli',
        ],
    ]);

    expect($org)->toMatchArray([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'Acta Populi',
        'url' => 'https://voxpopuli.digital',
        'logo' => 'https://voxpopuli.digital/logo.png',
        'sameAs' => [
            'https://bsky.app/profile/voxpopuli',
            'https://facebook.com/voxpopuli',
        ],
    ]);
});

it('JsonLd omits logo and sameAs when not provided', function () {
    $jsonld = new JsonLd();
    $org = $jsonld->organization([
        'name' => 'Minimal Org',
        'url' => 'https://example.com',
    ]);

    expect($org)->toHaveKey('name');
    expect($org)->not->toHaveKey('logo');
    expect($org)->not->toHaveKey('sameAs');
});

it('JsonLd renders WebSite schema with SearchAction', function () {
    $jsonld = new JsonLd();
    $website = $jsonld->website([
        'name' => 'Acta Populi',
        'url' => 'https://voxpopuli.digital',
        'search_url' => 'https://voxpopuli.digital/?s={search_term_string}',
    ]);

    expect($website)->toMatchArray([
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
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

it('JsonLd renders Article schema for single post', function () {
    $jsonld = new JsonLd();
    $article = $jsonld->article([
        'headline' => 'Test Article',
        'description' => 'Article description',
        'datePublished' => '2026-07-08T10:00:00+00:00',
        'dateModified' => '2026-07-08T12:00:00+00:00',
        'author' => 'Jane Doe',
        'image' => 'https://voxpopuli.digital/image.jpg',
        'url' => 'https://voxpopuli.digital/article',
    ]);

    expect($article)->toMatchArray([
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => 'Test Article',
        'author' => ['@type' => 'Person', 'name' => 'Jane Doe'],
    ]);
});

it('SeoMeta provides meta description', function () {
    $seo = new SeoMeta(['meta_desc' => 'Test description']);
    expect($seo->getMetaDescription())->toBe('Test description');
});

it('SeoMeta truncates long meta description to 160 chars', function () {
    $long = str_repeat('a', 200);
    $seo = new SeoMeta(['meta_desc' => $long]);
    expect(strlen($seo->getMetaDescription()))->toBe(160);
});

it('SeoMeta returns post title for home page', function () {
    $seo = new SeoMeta([
        'post_title' => 'Acta Populi',
        'is_home' => true,
    ]);
    expect($seo->getPostTitle())->toBe('Acta Populi');
    expect($seo->isHome())->toBeTrue();
});

it('SeoMeta fallback applies OG title from post title', function () {
    $seo = new SeoMeta([
        'post_title' => 'Fallback Title',
    ], true);

    expect($seo->getOgTitle())->toBe('Fallback Title');
});

it('SeoMeta fallback applies OG description from meta description', function () {
    $seo = new SeoMeta([
        'meta_desc' => 'Fallback Desc',
    ], true);

    expect($seo->getOgDescription())->toBe('Fallback Desc');
});

it('MetaRenderer renders complete head output for a single post', function () {
    $seo = new SeoMeta([
        'og_title' => 'Post Title',
        'og_desc' => 'Post description',
        'meta_desc' => 'Meta description',
        'post_url' => 'https://voxpopuli.digital/post',
        'canonical' => 'https://voxpopuli.digital/post',
        'og_image_url' => 'https://voxpopuli.digital/image.jpg',
        'post_type' => 'post',
        'is_home' => false,
    ]);
    $renderer = new MetaRenderer($seo);
    $output = $renderer->render();

    expect($output)->toContain('<meta property="og:title" content="Post Title">');
    expect($output)->toContain('<meta property="og:description" content="Post description">');
    expect($output)->toContain('<meta property="og:image" content="https://voxpopuli.digital/image.jpg">');
    expect($output)->toContain('<meta property="og:url" content="https://voxpopuli.digital/post">');
    expect($output)->toContain('<meta property="og:type" content="article">');
    expect($output)->toContain('<meta property="og:site_name"');
    expect($output)->toContain('<meta name="twitter:card" content="summary_large_image">');
    expect($output)->toContain('<meta name="description" content="Meta description">');
    expect($output)->toContain('<link rel="canonical" href="https://voxpopuli.digital/post">');
});

it('MetaRenderer renders homepage with website OG type', function () {
    $seo = new SeoMeta([
        'post_title' => 'Home',
        'is_home' => true,
        'post_url' => 'https://voxpopuli.digital',
        'canonical' => 'https://voxpopuli.digital',
    ]);
    $renderer = new MetaRenderer($seo);
    $output = $renderer->render();

    expect($output)->toContain('<meta property="og:type" content="website">');
});

it('MetaRenderer outputs noindex when configured', function () {
    $seo = new SeoMeta(['noindex' => true]);
    $renderer = new MetaRenderer($seo);
    $output = $renderer->render();

    expect($output)->toContain('<meta name="robots" content="noindex">');
});
