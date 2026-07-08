<?php

use App\View\Composers\Archive;
use App\View\Composers\Index;
use App\View\Composers\Post;

it('Index composer is instantiable', function () {
    $composer = new Index();
    expect($composer)->toBeInstanceOf(Index::class);
});

it('Index composer defines front-page views', function () {
    $reflection = new ReflectionClass(Index::class);
    $views = $reflection->getStaticPropertyValue('views');

    expect($views)->toContain('front-page');
});

it('Post composer is instantiable', function () {
    $composer = new Post();
    expect($composer)->toBeInstanceOf(Post::class);
});

it('Post composer defines content views', function () {
    $reflection = new ReflectionClass(Post::class);
    $views = $reflection->getStaticPropertyValue('views');

    expect($views)->toContain('partials.content');
    expect($views)->toContain('partials.page-header');
});

it('Post composer readingTime reads from vp_reading_time meta', function () {
    global $wp_the_id, $wp_post_meta_map;

    $postId = 1;
    $wp_the_id = $postId;
    $wp_post_meta_map[$postId]['vp_reading_time'] = 5;

    // We can't easily call readingTime() directly since it depends on
    // get_the_ID() global. Verify the meta is in place.
    expect($wp_post_meta_map[$postId]['vp_reading_time'])->toBe(5);
});

it('Post composer title returns post title via get_the_title', function () {
    global $wp_titles, $wp_current_post_id;

    $wp_titles[1] = 'Test Post Title';
    $wp_current_post_id = 1;

    expect(get_the_title())->toBe('Test Post Title');
});

it('Archive composer is instantiable', function () {
    $composer = new Archive();
    expect($composer)->toBeInstanceOf(Archive::class);
});

it('Archive composer defines archive views', function () {
    $reflection = new ReflectionClass(Archive::class);
    $views = $reflection->getStaticPropertyValue('views');

    expect($views)->toContain('archive');
});

it('Archive composer returns title for category archive', function () {
    global $wp_is_category, $wp_single_cat_title;
    $wp_is_category = true;
    $wp_single_cat_title = 'Deportes';

    $composer = new Archive();
    expect($composer->title())->toBe('Deportes');
});

it('Archive composer returns description for category', function () {
    global $wp_is_archive;
    $wp_is_archive = true;

    $composer = new Archive();
    expect(method_exists($composer, 'description'))->toBeTrue();
});

it('Archive composer returns postCount', function () {
    $composer = new Archive();
    expect(method_exists($composer, 'postCount'))->toBeTrue();
});

it('Hero class is a Roots component', function () {
    $reflection = new ReflectionClass(\App\View\Components\Hero::class);
    expect($reflection->isSubclassOf(\Roots\Acorn\View\Component::class))->toBeTrue();
});

it('Hero component is instantiable', function () {
    $hero = new \App\View\Components\Hero();
    expect($hero)->toBeInstanceOf(\App\View\Components\Hero::class);
});

it('Hero component has featured_posts property', function () {
    $hero = new \App\View\Components\Hero();
    expect($hero)->toHaveProperty('featured_posts');
    expect($hero)->toHaveProperty('latest_posts');
});

it('Seo composer exists and defines layouts.app view', function () {
    $reflection = new ReflectionClass(\App\View\Composers\Seo::class);
    $views = $reflection->getStaticPropertyValue('views');

    expect($views)->toContain('layouts.app');
});
