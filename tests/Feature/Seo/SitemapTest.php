<?php

use App\Seo\Sitemap;

it('generates valid XML with correct namespace', function () {
    $sitemap = new Sitemap([]);

    $xml = $sitemap->toXml();

    expect($xml)->toStartWith('<?xml version="1.0" encoding="UTF-8"?>');
    expect($xml)->toContain('xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"');
});

it('includes urlset root element', function () {
    $sitemap = new Sitemap([]);

    $xml = $sitemap->toXml();

    expect($xml)->toContain('<urlset');
    expect($xml)->toContain('</urlset>');
});

it('includes url entries for published posts', function () {
    $sitemap = new Sitemap([
        [
            'loc' => 'https://example.com/post-1',
            'lastmod' => '2026-01-15',
            'priority' => '0.7',
            'changefreq' => 'weekly',
        ],
        [
            'loc' => 'https://example.com/post-2',
            'lastmod' => '2026-01-16',
            'priority' => '0.6',
            'changefreq' => 'monthly',
        ],
    ]);

    $xml = $sitemap->toXml();

    expect($xml)->toContain('<loc>https://example.com/post-1</loc>');
    expect($xml)->toContain('<loc>https://example.com/post-2</loc>');
    expect($xml)->toContain('<lastmod>2026-01-15</lastmod>');
    expect($xml)->toContain('<lastmod>2026-01-16</lastmod>');
});

it('includes priority and changefreq for each url entry', function () {
    $sitemap = new Sitemap([
        [
            'loc' => 'https://example.com/post',
            'lastmod' => '2026-01-15',
            'priority' => '0.8',
            'changefreq' => 'daily',
        ],
    ]);

    $xml = $sitemap->toXml();

    expect($xml)->toContain('<priority>0.8</priority>');
    expect($xml)->toContain('<changefreq>daily</changefreq>');
});

it('returns valid XML with zero url entries for empty sitemap', function () {
    $sitemap = new Sitemap([]);

    $xml = $sitemap->toXml();

    expect($xml)->toContain('<urlset');
    expect($xml)->toContain('</urlset>');
    expect($xml)->not->toContain('<url>');
});

it('sets default priority for posts to 0.7', function () {
    $sitemap = new Sitemap([
        ['loc' => 'https://example.com/post', 'lastmod' => '2026-01-15', 'type' => 'post'],
    ]);

    $xml = $sitemap->toXml();

    expect($xml)->toContain('<priority>0.7</priority>');
});

it('sets default priority for pages to 0.8', function () {
    $sitemap = new Sitemap([
        ['loc' => 'https://example.com/page', 'lastmod' => '2026-01-15', 'type' => 'page'],
    ]);

    $xml = $sitemap->toXml();

    expect($xml)->toContain('<priority>0.8</priority>');
});

it('sets default changefreq for posts to weekly', function () {
    $sitemap = new Sitemap([
        ['loc' => 'https://example.com/post', 'lastmod' => '2026-01-15', 'type' => 'post'],
    ]);

    $xml = $sitemap->toXml();

    expect($xml)->toContain('<changefreq>weekly</changefreq>');
});

it('sets default changefreq for pages to monthly', function () {
    $sitemap = new Sitemap([
        ['loc' => 'https://example.com/page', 'lastmod' => '2026-01-15', 'type' => 'page'],
    ]);

    $xml = $sitemap->toXml();

    expect($xml)->toContain('<changefreq>monthly</changefreq>');
});

it('uses custom priority and changefreq when provided', function () {
    $sitemap = new Sitemap([
        [
            'loc' => 'https://example.com/post',
            'lastmod' => '2026-01-15',
            'priority' => '1.0',
            'changefreq' => 'always',
        ],
    ]);

    $xml = $sitemap->toXml();

    expect($xml)->toContain('<priority>1.0</priority>');
    expect($xml)->toContain('<changefreq>always</changefreq>');
});

it('returns correct Content-Type header', function () {
    $sitemap = new Sitemap([]);

    expect($sitemap->getContentType())->toBe('application/xml');
});

it('returns Cache-Control header', function () {
    $sitemap = new Sitemap([]);

    expect($sitemap->getCacheControl())->toBe('public, max-age=3600');
});

it('sets Last-Modified from latest post date', function () {
    $sitemap = new Sitemap([
        ['loc' => 'https://example.com/old', 'lastmod' => '2026-01-10'],
        ['loc' => 'https://example.com/new', 'lastmod' => '2026-01-20'],
    ]);

    expect($sitemap->getLastModified())->toBe('2026-01-20');
});

it('returns null Last-Modified for empty sitemap', function () {
    $sitemap = new Sitemap([]);

    expect($sitemap->getLastModified())->toBeNull();
});

it('escapes XML special characters in URLs', function () {
    $sitemap = new Sitemap([
        ['loc' => 'https://example.com/post?foo=bar&baz=qux', 'lastmod' => '2026-01-15'],
    ]);

    $xml = $sitemap->toXml();

    expect($xml)->toContain('&amp;');
    expect($xml)->not->toContain('&baz=');
});

it('returns the XML as a string from toString', function () {
    $sitemap = new Sitemap([]);

    $result = (string) $sitemap;

    expect($result)->toStartWith('<?xml version="1.0" encoding="UTF-8"?>');
});
