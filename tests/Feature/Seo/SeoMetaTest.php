<?php

use App\Seo\SeoMeta;

beforeEach(function () {
    // Mock WordPress functions needed by SeoMeta
});

it('resolves meta description from post meta', function () {
    $seo = new SeoMeta([
        'meta_desc' => 'This is a meta description',
    ]);

    expect($seo->getMetaDescription())->toBe('This is a meta description');
});

it('returns null for empty meta description', function () {
    $seo = new SeoMeta([]);

    expect($seo->getMetaDescription())->toBeNull();
});

it('truncates meta description to 160 characters', function () {
    $longDesc = str_repeat('a', 200);
    $seo = new SeoMeta([
        'meta_desc' => $longDesc,
    ]);

    $result = $seo->getMetaDescription();

    expect($result)->toHaveLength(160);
    expect($result)->toBe(str_repeat('a', 160));
});

it('returns og title from post meta', function () {
    $seo = new SeoMeta([
        'og_title' => 'Open Graph Title',
    ]);

    expect($seo->getOgTitle())->toBe('Open Graph Title');
});

it('falls back to post title for og title', function () {
    $seo = new SeoMeta([
        'post_title' => 'Post Title',
    ], fallbackToPost: true);

    expect($seo->getOgTitle())->toBe('Post Title');
});

it('returns null for empty og title without fallback', function () {
    $seo = new SeoMeta([]);

    expect($seo->getOgTitle())->toBeNull();
});

it('returns og description from post meta', function () {
    $seo = new SeoMeta([
        'og_desc' => 'OG Description',
    ]);

    expect($seo->getOgDescription())->toBe('OG Description');
});

it('falls back to meta description for og description', function () {
    $seo = new SeoMeta([
        'meta_desc' => 'Meta Desc',
    ], fallbackToPost: true);

    expect($seo->getOgDescription())->toBe('Meta Desc');
});

it('returns null for empty og description', function () {
    $seo = new SeoMeta([]);

    expect($seo->getOgDescription())->toBeNull();
});

it('returns og image ID from post meta', function () {
    $seo = new SeoMeta([
        'og_image_id' => 42,
    ]);

    expect($seo->getOgImageId())->toBe(42);
});

it('returns null for empty og image ID', function () {
    $seo = new SeoMeta([]);

    expect($seo->getOgImageId())->toBeNull();
});

it('returns noindex from post meta', function () {
    $seo = new SeoMeta([
        'noindex' => true,
    ]);

    expect($seo->getNoindex())->toBeTrue();
});

it('returns false for noindex when not set', function () {
    $seo = new SeoMeta([]);

    expect($seo->getNoindex())->toBeFalse();
});

it('returns canonical URL from post meta', function () {
    $seo = new SeoMeta([
        'canonical' => 'https://example.com/canonical',
    ]);

    expect($seo->getCanonical())->toBe('https://example.com/canonical');
});

it('returns null for empty canonical URL', function () {
    $seo = new SeoMeta([]);

    expect($seo->getCanonical())->toBeNull();
});

it('validates canonical URL discarding invalid ones', function () {
    $seo = new SeoMeta([
        'canonical' => 'not-a-valid-url',
    ]);

    expect($seo->getCanonical())->toBeNull();
});

it('returns post type from data', function () {
    $seo = new SeoMeta([
        'post_type' => 'post',
    ]);

    expect($seo->getPostType())->toBe('post');
});

it('defaults post type to post', function () {
    $seo = new SeoMeta([]);

    expect($seo->getPostType())->toBe('post');
});

it('returns post title from data', function () {
    $seo = new SeoMeta([
        'post_title' => 'Hello World',
    ]);

    expect($seo->getPostTitle())->toBe('Hello World');
});

it('returns null for empty post title', function () {
    $seo = new SeoMeta([]);

    expect($seo->getPostTitle())->toBeNull();
});

it('returns post URL from data', function () {
    $seo = new SeoMeta([
        'post_url' => 'https://example.com/post',
    ]);

    expect($seo->getPostUrl())->toBe('https://example.com/post');
});

it('returns null for empty post URL', function () {
    $seo = new SeoMeta([]);

    expect($seo->getPostUrl())->toBeNull();
});

it('returns published date from data', function () {
    $seo = new SeoMeta([
        'date_published' => '2026-01-15T10:00:00+00:00',
    ]);

    expect($seo->getDatePublished())->toBe('2026-01-15T10:00:00+00:00');
});

it('returns null for empty published date', function () {
    $seo = new SeoMeta([]);

    expect($seo->getDatePublished())->toBeNull();
});

it('returns modified date from data', function () {
    $seo = new SeoMeta([
        'date_modified' => '2026-01-16T10:00:00+00:00',
    ]);

    expect($seo->getDateModified())->toBe('2026-01-16T10:00:00+00:00');
});

it('returns author name from data', function () {
    $seo = new SeoMeta([
        'author_name' => 'John Doe',
    ]);

    expect($seo->getAuthorName())->toBe('John Doe');
});

it('returns null for empty author name', function () {
    $seo = new SeoMeta([]);

    expect($seo->getAuthorName())->toBeNull();
});

it('resolves og image URL when image ID is provided', function () {
    $seo = new SeoMeta([
        'og_image_id' => 42,
        'og_image_url' => 'https://example.com/image.jpg',
    ]);

    expect($seo->getOgImageUrl())->toBe('https://example.com/image.jpg');
});

it('returns null for og image URL when no image set', function () {
    $seo = new SeoMeta([]);

    expect($seo->getOgImageUrl())->toBeNull();
});

it('preserves OG title on fallbackToPost when post title empty', function () {
    $seo = new SeoMeta([], fallbackToPost: true);

    expect($seo->getOgTitle())->toBeNull();
    expect($seo->getOgDescription())->toBeNull();
});
