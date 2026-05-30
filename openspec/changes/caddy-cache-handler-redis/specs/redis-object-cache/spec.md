# Redis Object Cache Specification

## Purpose

WordPress object cache backend using Redis via phpredis, replacing SQLite transient and query-cache storage. The `wp_cache_*` function family works transparently. Falls back gracefully if the Redis server is unreachable.

## Requirements

### Requirement: PHP Redis extension as primary driver

The system MUST use the `redis` PHP extension (phpredis, built from C) as the primary Redis driver.

#### Scenario: PhpRedis is active and operational

- GIVEN the `redis` PHP extension is loaded and the Redis server is reachable
- WHEN WordPress calls `wp_cache_set()` / `wp_cache_get()`
- THEN operations execute against Redis with sub-millisecond latency

### Requirement: REDIS_URL environment configuration

The system MUST connect to Redis using the `REDIS_URL` environment variable. The URL format SHALL be `redis://<user>:<password>@<host>:<port>`.

#### Scenario: Connection succeeds with valid REDIS_URL

- GIVEN `REDIS_URL` is set to a valid Redis connection string
- WHEN the object cache drop-in initializes
- THEN a connection to the Redis server is established
- AND `wp_using_ext_object_cache()` returns `true`

### Requirement: Key namespacing via WP_REDIS_PREFIX

The system MUST prefix all Redis keys with a configurable namespace set via `WP_REDIS_PREFIX`.

#### Scenario: Prefixed keys avoid cross-site collisions

- GIVEN `WP_REDIS_PREFIX` is set to `voxpopuli`
- WHEN a `wp_cache_set('my_key', $data)` call is made
- THEN the stored Redis key is `voxpopuli:my_key`

### Requirement: Compatibility with FrankenPHP threaded (ZTS) mode

The system MUST operate correctly under FrankenPHP's ZTS (Zend Thread Safety) mode where multiple PHP worker threads share a single process. The phpredis extension SHALL be compiled with thread safety support.

#### Scenario: Multiple worker threads use same Redis connection

- GIVEN FrankenPHP runs with multiple worker threads
- WHEN two threads simultaneously call `wp_cache_get()`
- THEN both calls succeed without connection corruption or crashes

### Requirement: Graceful degradation on Redis unavailability

The system MUST fall back to WordPress's internal (array-based) object cache if Redis is unreachable. The site MUST continue to function without errors.

#### Scenario: Redis server is down at request time

- GIVEN the Redis server is unreachable
- WHEN any page is requested
- THEN WordPress's internal object cache takes over transparently
- AND no PHP errors or warnings are raised
- AND `wp_using_ext_object_cache()` returns `false`

### Requirement: Transparent wp_cache_* API compatibility

The system MUST support all standard `wp_cache_*` functions: `get`, `set`, `add`, `delete`, `flush`, `replace`, `get_multiple`, `incr`, `decr`.

#### Scenario: Full wp_cache API surface works

- GIVEN Redis is connected and operational
- WHEN `wp_cache_set()` stores a value, `wp_cache_get()` retrieves it
- THEN `wp_cache_get()` returns the exact stored value
- AND `wp_cache_delete()` removes it (subsequent `get` returns `false`)
- AND `wp_cache_flush()` removes all keys under the current prefix
