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
