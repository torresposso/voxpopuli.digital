<?php

use App\Seo\TitleExpander;

it('expands %%title%% to post title', function () {
    $result = TitleExpander::expand('%%title%%', [
        'title' => 'Actual Post Title',
        'sep' => '-',
        'sitename' => 'My Site',
    ]);

    expect($result)->toBe('Actual Post Title');
});

it('expands %%sep%% to configured separator', function () {
    $result = TitleExpander::expand('%%sep%%', [
        'title' => 'Title',
        'sep' => '|',
        'sitename' => 'My Site',
    ]);

    expect($result)->toBe('|');
});

it('expands %%sitename%% to site name', function () {
    $result = TitleExpander::expand('%%sitename%%', [
        'title' => 'Title',
        'sep' => '-',
        'sitename' => 'My Awesome Site',
    ]);

    expect($result)->toBe('My Awesome Site');
});

it('expands %%page%% to page number', function () {
    $result = TitleExpander::expand('%%page%%', [
        'title' => 'Title',
        'sep' => '-',
        'sitename' => 'Site',
        'page' => 3,
    ]);

    expect($result)->toBe('3');
});

it('expands combined %%title%% %%sep%% %%sitename%%', function () {
    $result = TitleExpander::expand('%%title%% %%sep%% %%sitename%%', [
        'title' => 'Actual Post Title',
        'sep' => '-',
        'sitename' => 'My Site Name',
    ]);

    expect($result)->toBe('Actual Post Title - My Site Name');
});

it('returns value unchanged when no variables present', function () {
    $result = TitleExpander::expand('Plain Title Without Variables', [
        'title' => 'Post Title',
        'sep' => '-',
        'sitename' => 'Site',
    ]);

    expect($result)->toBe('Plain Title Without Variables');
});

it('defaults %%page%% to empty string when not provided', function () {
    $result = TitleExpander::expand('Page %%page%%', [
        'title' => 'Title',
        'sep' => '-',
        'sitename' => 'Site',
    ]);

    expect($result)->toBe('Page ');
});

it('defaults %%sep%% to - when not configured', function () {
    $result = TitleExpander::expand('%%title%% %%sep%% %%sitename%%', [
        'title' => 'Title',
        'sitename' => 'Site',
    ]);

    expect($result)->toBe('Title - Site');
});

it('handles empty title in context', function () {
    $result = TitleExpander::expand('%%title%% %%sep%% %%sitename%%', [
        'title' => '',
        'sep' => '-',
        'sitename' => 'Site',
    ]);

    expect($result)->toBe(' - Site');
});

it('handles empty input string', function () {
    $result = TitleExpander::expand('', [
        'title' => 'Title',
        'sep' => '-',
        'sitename' => 'Site',
    ]);

    expect($result)->toBe('');
});

it('filters HTML entities after expansion', function () {
    $result = TitleExpander::expand('%%title%%', [
        'title' => '<b>Bold</b> & more',
        'sep' => '-',
        'sitename' => 'Site',
    ]);

    expect($result)->toBe('Bold &amp; more');
});
