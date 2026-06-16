<?php

namespace Tests\Feature\Composers;

use App\View\Composers\Post;
use Illuminate\View\View;
use Mockery;

beforeEach(function () {
    global $mock_get_the_title;
    $mock_get_the_title = null;
});

afterEach(function () {
    Mockery::close();

    // Reset global state
    global $wp_is_home, $wp_is_archive, $wp_is_search, $wp_is_404, $wp_options, $wp_archive_title, $wp_search_query, $wp_titles, $wp_post_thumbnail_id, $wp_attachment_image_src, $wp_post_meta_map, $wp_attachment_caption, $mock_get_the_title;
    $wp_is_home = false;
    $wp_is_archive = false;
    $wp_is_search = false;
    $wp_is_404 = false;
    $wp_options = [];
    $wp_archive_title = '';
    $wp_search_query = '';
    $wp_titles = [];
    $wp_post_thumbnail_id = null;
    $wp_attachment_image_src = null;
    $wp_post_meta_map = [];
    $wp_attachment_caption = null;
    $mock_get_the_title = null;
});

it('returns post title when view is not page-header', function () {
    $view = Mockery::mock(View::class);
    $view->shouldReceive('name')->andReturn('partials.content');

    global $wp_titles;
    $wp_titles[0] = 'My Post Title';

    $composer = new Post();
    $reflection = new \ReflectionClass($composer);
    $property = $reflection->getProperty('view');
    $property->setAccessible(true);
    $property->setValue($composer, $view);

    expect($composer->title())->toBe('My Post Title');
});

it('returns title for home page when option page_for_posts is set', function () {
    $view = Mockery::mock(View::class);
    $view->shouldReceive('name')->andReturn('partials.page-header');

    global $wp_is_home, $wp_options, $wp_titles;
    $wp_is_home = true;
    $wp_options['page_for_posts'] = 123;
    $wp_titles[123] = 'Blog Title';

    $composer = new Post();
    $reflection = new \ReflectionClass($composer);
    $property = $reflection->getProperty('view');
    $property->setAccessible(true);
    $property->setValue($composer, $view);

    expect($composer->title())->toBe('Blog Title');
});

it('returns default title for home page when no page_for_posts', function () {
    $view = Mockery::mock(View::class);
    $view->shouldReceive('name')->andReturn('partials.page-header');

    global $wp_is_home, $wp_options;
    $wp_is_home = true;
    $wp_options['page_for_posts'] = false;

    $composer = new Post();
    $reflection = new \ReflectionClass($composer);
    $property = $reflection->getProperty('view');
    $property->setAccessible(true);
    $property->setValue($composer, $view);

    expect($composer->title())->toBe('Latest Posts');
});

it('returns archive title for archive pages', function () {
    $view = Mockery::mock(View::class);
    $view->shouldReceive('name')->andReturn('partials.page-header');

    global $wp_is_archive, $wp_archive_title;
    $wp_is_archive = true;
    $wp_archive_title = 'Archive: News';

    $composer = new Post();
    $reflection = new \ReflectionClass($composer);
    $property = $reflection->getProperty('view');
    $property->setAccessible(true);
    $property->setValue($composer, $view);

    expect($composer->title())->toBe('Archive: News');
});

it('returns search results title for search pages', function () {
    $view = Mockery::mock(View::class);
    $view->shouldReceive('name')->andReturn('partials.page-header');

    global $wp_is_search, $wp_search_query;
    $wp_is_search = true;
    $wp_search_query = 'test query';

    $composer = new Post();
    $reflection = new \ReflectionClass($composer);
    $property = $reflection->getProperty('view');
    $property->setAccessible(true);
    $property->setValue($composer, $view);

    expect($composer->title())->toBe('Search Results for test query');
});

it('returns not found title for 404 pages', function () {
    $view = Mockery::mock(View::class);
    $view->shouldReceive('name')->andReturn('partials.page-header');

    global $wp_is_404;
    $wp_is_404 = true;

    $composer = new Post();
    $reflection = new \ReflectionClass($composer);
    $property = $reflection->getProperty('view');
    $property->setAccessible(true);
    $property->setValue($composer, $view);

    expect($composer->title())->toBe('Not Found');
});

it('returns default title as fallback', function () {
    $view = Mockery::mock(View::class);
    $view->shouldReceive('name')->andReturn('partials.page-header');

    global $wp_titles;
    $wp_titles[0] = 'Fallback Title';

    $composer = new Post();
    $reflection = new \ReflectionClass($composer);
    $property = $reflection->getProperty('view');
    $property->setAccessible(true);
    $property->setValue($composer, $view);

    expect($composer->title())->toBe('Fallback Title');
});

it('returns pagination links', function () {
    $composer = new Post();

    // In tests/Pest.php we might need to mock wp_link_pages
    expect($composer->pagination())->toBe('');
});

it('returns null for featured image when no thumbnail id exists', function () {
    $composer = new Post();

    // Default get_post_thumbnail_id returns 0
    expect($composer->featuredImage())->toBeNull();
});

it('returns featured image array when thumbnail exists', function () {
    $composer = new Post();

    // We need to override get_post_thumbnail_id, wp_get_attachment_image_src, etc.
    // For now we'll write the test and update Pest.php
    global $wp_post_thumbnail_id, $wp_attachment_image_src, $wp_post_meta_map, $wp_attachment_caption, $wp_titles;
    $wp_post_thumbnail_id = 42;
    $wp_attachment_image_src = ['https://example.com/img.jpg', 800, 600];
    $wp_post_meta_map[42]['_wp_attachment_image_alt'] = 'Alt text';
    $wp_attachment_caption = 'Caption text';

    $result = $composer->featuredImage();

    expect($result)->toBeArray();
    expect($result['url'])->toBe('https://example.com/img.jpg');
    expect($result['width'])->toBe(800);
    expect($result['height'])->toBe(600);
    expect($result['alt'])->toBe('Alt text');
    expect($result['caption'])->toBe('Caption text');

    // Reset
    $wp_post_thumbnail_id = null;
    $wp_attachment_image_src = null;
    $wp_post_meta_map = [];
    $wp_attachment_caption = null;
});

it('returns post title as alt text when no alt text exists for featured image', function () {
    $composer = new Post();

    global $wp_post_thumbnail_id, $wp_attachment_image_src, $wp_post_meta_map, $wp_attachment_caption, $wp_titles;
    $wp_post_thumbnail_id = 42;
    $wp_attachment_image_src = ['https://example.com/img.jpg', 800, 600];
    $wp_post_meta_map[42]['_wp_attachment_image_alt'] = '';
    $wp_titles[0] = 'Fallback Alt Title';

    $result = $composer->featuredImage();

    expect($result['alt'])->toBe('Fallback Alt Title');

    $wp_post_thumbnail_id = null;
    $wp_attachment_image_src = null;
    $wp_post_meta_map = [];
    $wp_titles = [];
    $wp_post_thumbnail_id = null;
    $wp_attachment_image_src = null;
    $wp_post_meta_map = [];
    $wp_attachment_caption = null;
});

it('returns reading time using pre-computed meta', function () {
    $composer = new Post();

    global $wp_post_meta_map;
    // mock get_the_ID
    global $wp_the_id;
    $wp_the_id = 100;

    $wp_post_meta_map[100]['vp_reading_time'] = 5;

    expect($composer->readingTime())->toBe('5 min de lectura');
});

it('calculates reading time from content if meta is missing', function () {
    $composer = new Post();

    global $wp_post_meta_map, $wp_post_content_map, $wp_the_id;
    $wp_the_id = 100;
    $wp_post_meta_map[100]['vp_reading_time'] = false;

    // We need approx 400 words for 2 minutes (200 wpm)
    $wp_post_content_map[100] = str_repeat("word ", 400);

    expect($composer->readingTime())->toBe('2 min de lectura');
});

it('returns null for primary category if no categories exist', function () {
    $composer = new Post();

    global $wp_the_category;
    $wp_the_category = [];

    expect($composer->primaryCategory())->toBeNull();
});

it('returns Yoast primary category if exists', function () {
    $composer = new Post();

    global $wp_the_category, $wp_term;
    $category_mock = new \stdClass();
    $category_mock->name = 'Yoast Cat';
    $category_mock->term_id = 10;
    $category_mock->slug = 'yoast-cat';

    $wp_the_category = [$category_mock];
    $wp_term = $category_mock;

    global $wp_yoast_primary_term;
    $wp_yoast_primary_term = 10;

    $result = $composer->primaryCategory();

    expect($result['name'])->toBe('Yoast Cat');
    expect($result['link'])->toBe('link-to-cat-10');
    expect($result['slug'])->toBe('yoast-cat');
});

it('falls back to first category if Yoast fails', function () {
    $composer = new Post();

    global $wp_the_category, $wp_term, $wp_yoast_primary_term;

    $category_mock1 = new \stdClass();
    $category_mock1->name = 'First Cat';
    $category_mock1->term_id = 1;
    $category_mock1->slug = 'first-cat';

    $category_mock2 = new \stdClass();
    $category_mock2->name = 'Second Cat';
    $category_mock2->term_id = 2;
    $category_mock2->slug = 'second-cat';

    $wp_the_category = [$category_mock1, $category_mock2];
    $wp_yoast_primary_term = null; // No primary term

    $result = $composer->primaryCategory();

    expect($result['name'])->toBe('First Cat');
    expect($result['link'])->toBe('link-to-cat-1');
    expect($result['slug'])->toBe('first-cat');
});

it('returns empty array for suggested posts if no categories', function () {
    $composer = new Post();

    global $wp_post_categories;
    $wp_post_categories = [];

    expect($composer->suggestedPosts())->toBeArray()->toBeEmpty();
});

it('returns suggested posts', function () {
    $composer = new Post();

    global $wp_post_categories, $wp_query_mock, $wp_titles, $wp_the_date, $wp_the_category, $wp_permalinks;
    $wp_post_categories = [1, 2];

    $wp_query_mock = new \stdClass();
    $wp_query_mock->posts = [101, 102];
    $wp_query_mock->current_post = -1;

    $wp_titles[101] = 'Suggested 1';
    $wp_titles[102] = 'Suggested 2';
    $wp_permalinks[101] = 'https://link.com/1';
    $wp_permalinks[102] = 'https://link.com/2';
    $wp_the_date = '2023-01-01';

    $category_mock = new \stdClass();
    $category_mock->name = 'Some Cat';
    $wp_the_category = [$category_mock];

    $result = $composer->suggestedPosts();

    expect($result)->toHaveCount(2);
    expect($result[0]['title'])->toBe('Suggested 1');
    expect($result[0]['link'])->toBe('https://link.com/1');
    expect($result[1]['title'])->toBe('Suggested 2');

    $wp_query_mock = null;
    $wp_titles = [];
    $wp_permalinks = [];
});

it('returns null for latest featured post if no posts', function () {
    $composer = new Post();

    global $wp_query_mock;
    $wp_query_mock = new \stdClass();
    $wp_query_mock->posts = [];
    $wp_query_mock->current_post = -1;

    expect($composer->latestFeaturedPost())->toBeNull();

    $wp_query_mock = null;
});

it('returns latest featured post', function () {
    $composer = new Post();

    global $wp_query_mock, $wp_titles, $wp_the_date, $wp_the_category, $wp_permalinks, $wp_the_excerpt;
    $wp_query_mock = new \stdClass();
    $wp_query_mock->posts = [201];
    $wp_query_mock->current_post = -1;

    $wp_titles[201] = 'Featured Post';
    $wp_permalinks[201] = 'https://link.com/featured';
    $wp_the_date = '2023-02-01';
    $wp_the_excerpt = 'Featured excerpt';

    $category_mock = new \stdClass();
    $category_mock->name = 'Featured Cat';
    $wp_the_category = [$category_mock];

    $result = $composer->latestFeaturedPost();

    expect($result)->toBeArray();
    expect($result['title'])->toBe('Featured Post');
    expect($result['link'])->toBe('https://link.com/featured');
    expect($result['excerpt'])->toBe('Featured excerpt');

    $wp_query_mock = null;
    $wp_titles = [];
    $wp_permalinks = [];
});
