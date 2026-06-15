<?php

namespace Tests\Feature\Components;

use App\View\Components\Hero;
use Mockery;

afterEach(function () {
    Mockery::close();
});

test('it sets featured and latest posts in constructor', function () {
    $hero = Mockery::mock(Hero::class)->makePartial()->shouldAllowMockingProtectedMethods();

    $hero->shouldReceive('getHeroData')
        ->once()
        ->andReturn([
            'featured' => ['mock_featured_1', 'mock_featured_2'],
            'latest' => ['mock_latest_1', 'mock_latest_2'],
        ]);

    $hero->__construct();

    expect($hero->featured_posts)->toBe(['mock_featured_1', 'mock_featured_2'])
        ->and($hero->latest_posts)->toBe(['mock_latest_1', 'mock_latest_2']);
});

test('it uses WP_ENV logic for cache keys correctly', function () {
    $originalEnv = defined('WP_ENV') ? WP_ENV : null;

    if (!defined('WP_ENV')) {
        define('WP_ENV', 'development');
    }

    $_SERVER['HTTP_HOST'] = 'test.local';

    $key = Hero::getCacheKey();

    expect($key)->toStartWith('vp_hero_data_v2_');
});

test('it renders the component view', function () {
    $hero = Mockery::mock(Hero::class)->makePartial()->shouldAllowMockingProtectedMethods();

    $hero->shouldReceive('getHeroData')
        ->andReturn(['featured' => [], 'latest' => []]);

    $hero->__construct();

    // We mock the return of the view method which wraps the factory view helper
    $hero->shouldReceive('view')
        ->once()
        ->with('components.hero')
        ->andReturn('view_string');

    $view = $hero->render();

    expect($view)->toBe('view_string');
});
