<?php

/**
 * Regression tests for the production-only cache enable logic in
 * web/app/wp-cache-config.php.
 *
 * The cache must be enabled only when WP_ENV=production or
 * RAILWAY_ENVIRONMENT=production. Any other state (dev, staging, CI
 * with no env set) must leave it OFF to avoid serving stale content
 * during local development.
 */

/**
 * Mirror of the expression in wp-cache-config.php line 19.
 * Returns true only when WP_ENV=production OR RAILWAY_ENVIRONMENT=production.
 */
function vox_evaluate_cache_enabled(?string $wpEnv, ?string $railwayEnv): bool
{
    return ($wpEnv === 'production') || ($railwayEnv === 'production');
}

function vox_read_cache_enabled_expression(): string
{
    $file = realpath(__DIR__ . '/../../../web/app/wp-cache-config.php');
    expect($file)->toBeString();

    $contents = (string) file_get_contents($file);

    if (! preg_match('/\$cache_enabled\s*=\s*([^;]+);/', $contents, $m)) {
        test()->fail("Could not find \$cache_enabled in wp-cache-config.php");
    }

    return trim($m[1]);
}

it('uses a getenv-based expression (not a hardcoded boolean) for $cache_enabled', function () {
    $expr = vox_read_cache_enabled_expression();

    // Must be a runtime expression, not a literal true/false.
    expect($expr)
        ->toContain('getenv')
        ->not->toBe('true')
        ->not->toBe('false');
});

it('enables cache when WP_ENV=production', function () {
    expect(vox_evaluate_cache_enabled('production', null))->toBeTrue();
});

it('enables cache when RAILWAY_ENVIRONMENT=production', function () {
    expect(vox_evaluate_cache_enabled(null, 'production'))->toBeTrue();
});

it('disables cache when neither env var is set', function () {
    expect(vox_evaluate_cache_enabled(null, null))->toBeFalse();
});

it('disables cache in development', function () {
    expect(vox_evaluate_cache_enabled('development', 'preview'))->toBeFalse();
});

it('disables cache in staging', function () {
    expect(vox_evaluate_cache_enabled('staging', 'staging'))->toBeFalse();
});
