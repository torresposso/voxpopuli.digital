# HTTP Response Cache Specification

## Purpose

RFC 7234 HTTP-level response caching at the Caddy reverse-proxy layer, serving anonymous HTML requests from embedded Badger storage. Logged-in users and admin paths bypass the cache entirely.

## Requirements

### Requirement: Badger-backed cache storage

The system MUST store cached HTTP responses in Badger embedded key-value storage at `/data/caddy/cache`.

#### Scenario: Cache miss populates Badger on first request

- GIVEN a first-time anonymous visitor requests `/`
- WHEN the request passes through the `cache` directive
- THEN the cache is empty (miss) and the request is forwarded to `php_server`
- AND the response is stored in Badger for subsequent requests

### Requirement: Default TTL and stale-while-revalidate

The system MUST serve cached responses with a default TTL of 5 minutes and MUST support stale-while-revalidate with a 1-minute window.

#### Scenario: Fresh cache hit returns within TTL

- GIVEN a cached HTML response stored less than 5 minutes ago
- WHEN an anonymous visitor requests the same URL
- THEN the system returns the cached response within approximately 1ms
- AND the response includes an `Age` header reflecting seconds since storage

#### Scenario: Stale response triggers revalidation within stale window

- GIVEN a cached response stored between 5 and 6 minutes ago
- WHEN an anonymous visitor requests the same URL
- THEN the system MAY serve the stale response while asynchronously revalidating
- AND subsequent requests receive the fresh response once revalidated

### Requirement: Cookie-based bypass for authenticated sessions

The system MUST bypass the cache when the request contains a `wordpress_logged_in*` cookie.

#### Scenario: Logged-in user always gets fresh response

- GIVEN a request includes a `wordpress_logged_in_<hash>` cookie
- WHEN the request reaches the `cache` directive
- THEN the request MUST bypass the cache entirely and pass through to `php_server`
- AND the response MUST NOT include an `X-Cache: hit` header

### Requirement: Admin path bypass

The system MUST bypass the cache for `wp-admin/*` and `wp-login.php` URL paths.

#### Scenario: Admin area never cached

- GIVEN a request targets `/wp-admin/*` or `/wp-login.php`
- WHEN the request reaches the `cache` directive
- THEN the request MUST bypass the cache and pass through to `php_server`
- AND the response MUST NOT be written to the cache

### Requirement: Cache hit/miss headers

The system MUST attach `X-Cache: hit` or `X-Cache: miss` headers to every response processed by the `cache` directive.

#### Scenario: Cache hit returns X-Cache header

- GIVEN a cached response exists for the requested URL
- WHEN the cache serves that response
- THEN the response includes `X-Cache: hit` and an `Age` header

#### Scenario: Cache miss returns pass-through

- GIVEN no cached response exists for the requested URL
- WHEN the cache forwards to `php_server`
- THEN the response includes `X-Cache: miss`

### Requirement: On-demand cache purge via PURGE request

The system MUST support the `PURGE` HTTP method to evict a specific URL from the cache.

#### Scenario: PURGE request invalidates single URL

- GIVEN a cached response exists at `https://example.com/page`
- WHEN a `PURGE` request is sent to `https://example.com/page`
- THEN the cached entry is evicted from Badger storage
- AND the next GET request returns a fresh response with `X-Cache: miss`

### Requirement: Uncacheable HTTP methods

The system MUST NOT cache responses for POST, PUT, DELETE, PATCH, or PURGE requests.

#### Scenario: POST requests always pass through

- GIVEN a POST request to any URL
- WHEN the request reaches the `cache` directive
- THEN the request passes through to `php_server`
- AND the response is NOT cached
