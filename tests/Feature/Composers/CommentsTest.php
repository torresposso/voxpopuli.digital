<?php

use App\View\Composers\Comments;

// Mock necessary WordPress functions
if (!function_exists('get_comments_number')) {
    function get_comments_number($post_id = 0)
    {
        global $mock_get_comments_number;
        return $mock_get_comments_number ?? 0;
    }
}
if (!function_exists('_nx')) {
    function _nx($single, $plural, $number, $context, $domain)
    {
        return $number === 1 ? $single : $plural;
    }
}
if (!function_exists('_x')) {
    function _x($text, $context, $domain)
    {
        return $text;
    }
}
if (!function_exists('number_format_i18n')) {
    function number_format_i18n($number, $decimals = 0)
    {
        return (string) $number;
    }
}
if (!function_exists('have_comments')) {
    function have_comments()
    {
        global $mock_have_comments;
        return $mock_have_comments ?? false;
    }
}
if (!function_exists('wp_list_comments')) {
    function wp_list_comments($args = [])
    {
        global $mock_wp_list_comments;
        return $mock_wp_list_comments ?? 'Mock Comments List';
    }
}
if (!function_exists('get_previous_comments_link')) {
    function get_previous_comments_link($label = '')
    {
        global $mock_get_previous_comments_link;
        return $mock_get_previous_comments_link ?? null;
    }
}
if (!function_exists('__')) {
    function __($text, $domain = 'default')
    {
        return $text;
    }
}
if (!function_exists('get_next_comments_link')) {
    function get_next_comments_link($label = '')
    {
        global $mock_get_next_comments_link;
        return $mock_get_next_comments_link ?? null;
    }
}
if (!function_exists('get_comment_pages_count')) {
    function get_comment_pages_count()
    {
        global $mock_get_comment_pages_count;
        return $mock_get_comment_pages_count ?? 1;
    }
}
if (!function_exists('get_option')) {
    function get_option($option, $default = false)
    {
        global $mock_get_option_page_comments;
        if ($option === 'page_comments') {
            return $mock_get_option_page_comments ?? false;
        }
        return $default;
    }
}
if (!function_exists('comments_open')) {
    function comments_open($post_id = null)
    {
        global $mock_comments_open;
        return $mock_comments_open ?? true;
    }
}
if (!function_exists('post_type_supports')) {
    function post_type_supports($post_type, $feature)
    {
        global $mock_post_type_supports;
        return $mock_post_type_supports ?? true;
    }
}
if (!function_exists('get_post_type')) {
    function get_post_type($post = null)
    {
        global $mock_get_post_type;
        return $mock_get_post_type ?? 'post';
    }
}

require_once __DIR__ . '/../../../web/app/themes/voxpopuli/app/View/Composers/Comments.php';

uses()->beforeEach(function () {
    global $mock_get_comments_number, $mock_get_the_title, $mock_have_comments, $mock_wp_list_comments,
    $mock_get_previous_comments_link, $mock_get_next_comments_link, $mock_get_comment_pages_count,
    $mock_get_option_page_comments, $mock_comments_open, $mock_post_type_supports, $mock_get_post_type;

    // Reset mocks before each test
    $mock_get_comments_number = 0;
    $mock_get_the_title = 'Test Post';
    $mock_have_comments = false;
    $mock_wp_list_comments = 'List of comments';
    $mock_get_previous_comments_link = null;
    $mock_get_next_comments_link = null;
    $mock_get_comment_pages_count = 1;
    $mock_get_option_page_comments = false;
    $mock_comments_open = true;
    $mock_post_type_supports = true;
    $mock_get_post_type = 'post';

    $this->composer = new Comments();
});

it('formats title correctly for zero comments', function () {
    global $mock_get_comments_number, $mock_get_the_title;
    $mock_get_comments_number = 0;
    $mock_get_the_title = 'My Post';

    $title = $this->composer->title();

    expect($title)->toBe('0 responses to &ldquo;My Post&rdquo;');
});

it('formats title correctly for one comment', function () {
    global $mock_get_comments_number, $mock_get_the_title;
    $mock_get_comments_number = 1;
    $mock_get_the_title = 'My Post';

    $title = $this->composer->title();

    expect($title)->toBe('One response to &ldquo;My Post&rdquo;');
});

it('formats title correctly for multiple comments', function () {
    global $mock_get_comments_number, $mock_get_the_title;
    $mock_get_comments_number = 5;
    $mock_get_the_title = 'My Post';

    $title = $this->composer->title();

    expect($title)->toBe('5 responses to &ldquo;My Post&rdquo;');
});

it('returns null for responses when no comments exist', function () {
    global $mock_have_comments;
    $mock_have_comments = false;

    expect($this->composer->responses())->toBeNull();
});

it('returns wp_list_comments when comments exist', function () {
    global $mock_have_comments, $mock_wp_list_comments;
    $mock_have_comments = true;
    $mock_wp_list_comments = '<ol><li>Comment 1</li></ol>';

    expect($this->composer->responses())->toBe('<ol><li>Comment 1</li></ol>');
});

it('returns null for previous when no link exists', function () {
    global $mock_get_previous_comments_link;
    $mock_get_previous_comments_link = null;

    expect($this->composer->previous())->toBeNull();
});

it('returns link for previous when link exists', function () {
    global $mock_get_previous_comments_link;
    $mock_get_previous_comments_link = '<a href="#">&larr; Older comments</a>';

    expect($this->composer->previous())->toBe('<a href="#">&larr; Older comments</a>');
});

it('returns null for next when no link exists', function () {
    global $mock_get_next_comments_link;
    $mock_get_next_comments_link = null;

    expect($this->composer->next())->toBeNull();
});

it('returns link for next when link exists', function () {
    global $mock_get_next_comments_link;
    $mock_get_next_comments_link = '<a href="#">Newer comments &rarr;</a>';

    expect($this->composer->next())->toBe('<a href="#">Newer comments &rarr;</a>');
});

it('determines if comments are paginated', function () {
    global $mock_get_comment_pages_count, $mock_get_option_page_comments;

    // Not paginated (pages <= 1)
    $mock_get_comment_pages_count = 1;
    $mock_get_option_page_comments = true;
    expect($this->composer->paginated())->toBeFalse();

    // Not paginated (option false)
    $mock_get_comment_pages_count = 2;
    $mock_get_option_page_comments = false;
    expect($this->composer->paginated())->toBeFalse();

    // Paginated
    $mock_get_comment_pages_count = 2;
    $mock_get_option_page_comments = true;
    expect($this->composer->paginated())->toBeTrue();
});

it('determines if comments are closed', function () {
    global $mock_comments_open, $mock_get_comments_number, $mock_post_type_supports;

    // Open
    $mock_comments_open = true;
    $mock_get_comments_number = 5;
    $mock_post_type_supports = true;
    expect($this->composer->closed())->toBeFalse();

    // Closed, but no comments
    $mock_comments_open = false;
    $mock_get_comments_number = 0;
    $mock_post_type_supports = true;
    expect($this->composer->closed())->toBeFalse();

    // Closed, comments exist, but post type doesn't support comments
    $mock_comments_open = false;
    $mock_get_comments_number = 5;
    $mock_post_type_supports = false;
    expect($this->composer->closed())->toBeFalse();

    // Closed, comments exist, post type supports comments
    $mock_comments_open = false;
    $mock_get_comments_number = 5;
    $mock_post_type_supports = true;
    expect($this->composer->closed())->toBeTrue();
});
