<?php

use App\Providers\PerformanceServiceProvider;

beforeEach(function () {
    PerformanceServiceProvider::resetSqlitePragmasAppliedForTesting();
});

afterEach(function () {
    Mockery::close();
    putenv('DB_APPLY_WAL_PRAGMA');
});

it('registers the service provider', function () {
    $provider = new PerformanceServiceProvider(app());

    expect($provider)->toBeInstanceOf(PerformanceServiceProvider::class);
});

it('has a boot method and an applySqlitePragmas static method', function () {
    $provider = new PerformanceServiceProvider(app());

    expect(method_exists($provider, 'boot'))->toBeTrue();
    expect(method_exists(PerformanceServiceProvider::class, 'applySqlitePragmas'))->toBeTrue();
});

it('does nothing when wpdb is null', function () {
    PerformanceServiceProvider::applySqlitePragmas(null);

    expect(true)->toBeTrue();
});

it('does nothing when wpdb has no dbh property', function () {
    $wpdb = new \stdClass();
    PerformanceServiceProvider::applySqlitePragmas($wpdb);

    expect(true)->toBeTrue();
});

it('skips PRAGMA application when dbh is not a WP_SQLite_Translator', function () {
    $dbh = new \stdClass();
    $wpdb = new \stdClass();
    $wpdb->dbh = $dbh;

    PerformanceServiceProvider::applySqlitePragmas($wpdb);

    expect(PerformanceServiceProvider::$pragmasApplied)->toBeTrue();
});

it('applies WAL, NORMAL, and busy_timeout PRAGMAs to a WP_SQLite_Translator dbh', function () {
    $dbh = Mockery::mock(\WP_SQLite_Translator::class);
    $dbh->shouldReceive('execute_sqlite_query')
        ->once()
        ->ordered()
        ->with('PRAGMA journal_mode = WAL');
    $dbh->shouldReceive('execute_sqlite_query')
        ->once()
        ->ordered()
        ->with('PRAGMA synchronous = NORMAL');
    $dbh->shouldReceive('execute_sqlite_query')
        ->once()
        ->ordered()
        ->with('PRAGMA busy_timeout = 5000');

    $wpdb = new \stdClass();
    $wpdb->dbh = $dbh;

    PerformanceServiceProvider::applySqlitePragmas($wpdb);
});

it('is idempotent: a second call within the same request does not re-apply PRAGMAs', function () {
    $callCount = 0;
    $dbh = Mockery::mock(\WP_SQLite_Translator::class);
    $dbh->shouldReceive('execute_sqlite_query')
        ->times(3)
        ->andReturnUsing(function () use (&$callCount) {
            $callCount++;
        });

    $wpdb = new \stdClass();
    $wpdb->dbh = $dbh;

    PerformanceServiceProvider::applySqlitePragmas($wpdb);
    PerformanceServiceProvider::applySqlitePragmas($wpdb);
    PerformanceServiceProvider::applySqlitePragmas($wpdb);

    expect($callCount)->toBe(3);
});

it('skips PRAGMA application when kill switch DB_APPLY_WAL_PRAGMA=false', function () {
    putenv('DB_APPLY_WAL_PRAGMA=false');

    $dbh = Mockery::mock(\WP_SQLite_Translator::class);
    $dbh->shouldNotReceive('execute_sqlite_query');

    $wpdb = new \stdClass();
    $wpdb->dbh = $dbh;

    PerformanceServiceProvider::applySqlitePragmas($wpdb);
});
