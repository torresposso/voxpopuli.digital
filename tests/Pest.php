<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
*/

expect()->extend('toStartWith', function (string $expected) {
    return $this->startsWith($expected);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
*/

if (! function_exists('app')) {
    /**
     * Minimal helper for SeoServiceProvider tests.
     */
    function app(?string $abstract = null, array $parameters = []): mixed
    {
        return $abstract ? null : new stdClass();
    }
}

if (! function_exists('get_bloginfo')) {
    /**
     * Stub for get_bloginfo.
     */
    function get_bloginfo(string $show = ''): string
    {
        return $show === 'name' ? 'Vox Populi' : '';
    }
}

if (! function_exists('get_locale')) {
    /**
     * Stub for get_locale.
     */
    function get_locale(): string
    {
        return 'es_ES';
    }
}
