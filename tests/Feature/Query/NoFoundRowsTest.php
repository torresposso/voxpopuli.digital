<?php

/**
 * Regression test: every WP_Query / get_posts() in the theme that has
 * a `posts_per_page` limit must also set `no_found_rows => true`.
 *
 * Background: the AGENTS.md "DOs and DONTs" rule #2 says:
 *   "DO NOT run standard WP_Query loops or get_posts() without setting
 *    'no_found_rows' => true unless you are actively rendering page
 *    numbers/pagination links."
 *
 * Without that flag, MySQL/SQLite executes the expensive
 * SQL_CALC_FOUND_ROWS query to compute total entries, which is severe
 * CPU overhead on lists, homepages, and sitemaps.
 *
 * This test scans every .php file under the theme and reports any
 * query call that violates the rule.
 */

function vox_find_query_violations(): array
{
    $themeRoot = realpath(__DIR__ . '/../../../web/app/themes/voxpopuli');
    if (! $themeRoot) {
        return [];
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($themeRoot, RecursiveDirectoryIterator::SKIP_DOTS),
    );

    $violations = [];

    foreach ($iterator as $file) {
        if (! $file->isFile() || $file->getExtension() !== 'php') {
            continue;
        }
        $path = $file->getPathname();
        $contents = (string) file_get_contents($path);

        // Match both `get_posts(` and `new WP_Query(` / `new \WP_Query(`.
        preg_match_all(
            '/(?P<call>get_posts\s*\(|new\s+\\?WP_Query\s*\()(?P<args>.*?)(?P<end>\)\s*;)/s',
            $contents,
            $matches,
            PREG_OFFSET_CAPTURE | PREG_SET_ORDER,
        );

        foreach ($matches as $m) {
            $args = $m['args'][0];
            $offset = $m['call'][1];

            // Only enforce on queries that paginate/limit.
            // The presence of `posts_per_page` is our heuristic for "this
            // query has a result-count limit", which is exactly when
            // SQL_CALC_FOUND_ROWS becomes wasteful.
            if (stripos($args, 'posts_per_page') === false
                && stripos($args, 'posts_per_archive_page') === false
                && stripos($args, 'numberposts') === false) {
                continue;
            }

            if (stripos($args, 'no_found_rows') !== false) {
                continue;
            }

            // Compute the line number for the violation.
            $line = substr_count(substr($contents, 0, $offset), "\n") + 1;

            $violations[] = [
                'file' => str_replace($themeRoot . '/', '', $path),
                'line' => $line,
                'snippet' => trim(substr($args, 0, 120)) . (strlen($args) > 120 ? '…' : ''),
            ];
        }
    }

    return $violations;
}

it('every limited WP_Query / get_posts() call sets no_found_rows => true', function () {
    $violations = vox_find_query_violations();

    if (! empty($violations)) {
        $report = "Found " . count($violations) . " query call(s) missing 'no_found_rows' => true:\n";
        foreach ($violations as $v) {
            $report .= sprintf("  - %s:%d\n      %s\n", $v['file'], $v['line'], $v['snippet']);
        }
        test()->fail($report);
    }

    expect($violations)->toBeArray()->toBeEmpty();
});
