<?php

use App\Seo\MetaRenderer;
use App\Seo\SeoMeta;

function renderer(SeoMeta $seo): MetaRenderer
{
    return new MetaRenderer($seo, 'Test Site', 'en_US');
}

it('renders OG title tag', function () {
    $seo = new SeoMeta(['og_title' => 'OG Title']);
    $renderer = renderer($seo);

    $output = $renderer->render();

    expect($output)->toContain('<meta property="og:title" content="OG Title">');
});

it('renders OG description tag', function () {
    $seo = new SeoMeta(['og_desc' => 'OG Description']);
    $renderer = renderer($seo);

    $output = $renderer->render();

    expect($output)->toContain('<meta property="og:description" content="OG Description">');
});

it('renders OG image tag with absolute URL', function () {
    $seo = new SeoMeta([
        'og_image_id' => 42,
        'og_image_url' => 'https://example.com/image.jpg',
    ]);
    $renderer = renderer($seo);

    $output = $renderer->render();

    expect($output)->toContain('<meta property="og:image" content="https://example.com/image.jpg">');
});

it('renders OG url tag', function () {
    $seo = new SeoMeta(['post_url' => 'https://example.com/post']);
    $renderer = renderer($seo);

    $output = $renderer->render();

    expect($output)->toContain('<meta property="og:url" content="https://example.com/post">');
});

it('renders OG type tag defaulting to article', function () {
    $seo = new SeoMeta([]);
    $renderer = renderer($seo);

    $output = $renderer->render();

    expect($output)->toContain('<meta property="og:type" content="article">');
});

it('renders OG type as website for homepage', function () {
    $seo = new SeoMeta(['is_home' => true]);
    $renderer = renderer($seo);

    $output = $renderer->render();

    expect($output)->toContain('<meta property="og:type" content="website">');
});

it('renders Twitter card tag', function () {
    $seo = new SeoMeta([]);
    $renderer = renderer($seo);

    $output = $renderer->render();

    expect($output)->toContain('<meta name="twitter:card" content="summary_large_image">');
});

it('renders Twitter title matching OG title', function () {
    $seo = new SeoMeta(['og_title' => 'OG Title']);
    $renderer = renderer($seo);

    $output = $renderer->render();

    expect($output)->toContain('<meta name="twitter:title" content="OG Title">');
});

it('renders Twitter description matching OG description', function () {
    $seo = new SeoMeta(['og_desc' => 'OG Desc']);
    $renderer = renderer($seo);

    $output = $renderer->render();

    expect($output)->toContain('<meta name="twitter:description" content="OG Desc">');
});

it('renders Twitter image matching OG image', function () {
    $seo = new SeoMeta([
        'og_image_id' => 42,
        'og_image_url' => 'https://example.com/image.jpg',
    ]);
    $renderer = renderer($seo);

    $output = $renderer->render();

    expect($output)->toContain('<meta name="twitter:image" content="https://example.com/image.jpg">');
});

it('renders meta description tag', function () {
    $seo = new SeoMeta(['meta_desc' => 'Meta Description']);
    $renderer = renderer($seo);

    $output = $renderer->render();

    expect($output)->toContain('<meta name="description" content="Meta Description">');
});

it('omits meta description tag when empty', function () {
    $seo = new SeoMeta([]);
    $renderer = renderer($seo);

    $output = $renderer->render();

    expect($output)->not->toContain('<meta name="description"');
});

it('renders canonical link tag', function () {
    $seo = new SeoMeta(['canonical' => 'https://example.com/canonical']);
    $renderer = renderer($seo);

    $output = $renderer->render();

    expect($output)->toContain('<link rel="canonical" href="https://example.com/canonical">');
});

it('omits canonical tag when empty', function () {
    $seo = new SeoMeta([]);
    $renderer = renderer($seo);

    $output = $renderer->render();

    expect($output)->not->toContain('<link rel="canonical"');
});

it('renders noindex robots tag when noindex is true', function () {
    $seo = new SeoMeta(['noindex' => true]);
    $renderer = renderer($seo);

    $output = $renderer->render();

    expect($output)->toContain('<meta name="robots" content="noindex">');
});

it('omits noindex robots tag when noindex is false', function () {
    $seo = new SeoMeta([]);
    $renderer = renderer($seo);

    $output = $renderer->render();

    expect($output)->not->toContain('<meta name="robots"');
});

it('omits OG image tag when no image set', function () {
    $seo = new SeoMeta([]);
    $renderer = renderer($seo);

    $output = $renderer->render();

    expect($output)->not->toContain('<meta property="og:image"');
});

it('omits OG url tag when no post URL set', function () {
    $seo = new SeoMeta([]);
    $renderer = renderer($seo);

    $output = $renderer->render();

    expect($output)->not->toContain('<meta property="og:url"');
});

it('renders multiple tags in one output', function () {
    $seo = new SeoMeta([
        'og_title' => 'Title',
        'og_desc' => 'Desc',
        'meta_desc' => 'Meta',
        'canonical' => 'https://example.com',
        'post_url' => 'https://example.com/post',
    ]);
    $renderer = renderer($seo);

    $output = $renderer->render();

    expect($output)->toContain('<meta property="og:title"');
    expect($output)->toContain('<meta property="og:description"');
    expect($output)->toContain('<meta property="og:url"');
    expect($output)->toContain('<meta property="og:type"');
    expect($output)->toContain('<meta name="description"');
    expect($output)->toContain('<link rel="canonical"');
    expect($output)->toContain('<meta name="twitter:card"');
    expect($output)->toContain("\n");
});
