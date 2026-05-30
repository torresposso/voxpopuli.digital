# Tasks: Caddy Cache Handler

## Review Workload Forecast

| Field | Value |
|-------|-------|
| Estimated changed lines | ~100-130 |
| 400-line budget risk | Low |
| Chained PRs recommended | No |
| Delivery strategy | single-pr |

Decision needed before apply: No
Chained PRs recommended: No
Chain strategy: single-pr
400-line budget risk: Low

## Phase 1: Docker — Custom FrankenPHP with cache-handler

- [ ] 1.1 Add `frankenphp-ext` builder stage: `FROM dunglas/frankenphp:php8.4-bookworm AS frankenphp-ext`, install xcaddy, build with `--with github.com/caddyserver/cache-handler`
- [ ] 1.2 Prod stage: copy `/usr/local/bin/frankenphp` from builder stage
- [ ] 1.3 Prod stage: ensure `mkdir -p /data/caddy/cache` for Badger storage

## Phase 2: Caddyfile — Cache config

- [ ] 2.1 Add global `cache {}` block: Badger at `/data/caddy/cache`, TTL 5m, stale 1m, regex exclude for `wp-admin` and `wp-login`
- [ ] 2.2 Add `handle @logged_in` block (cookie `wordpress_logged_in_*` match → `php_server`) before cached route
- [ ] 2.3 Add `route /` with `cache` directive + `php_server` for anonymous traffic

## Phase 3: Verification

- [ ] 3.1 Build Docker image locally, verify `frankenphp version` includes cache-handler
- [ ] 3.2 Verify cache miss → `X-Cache: miss` on first anonymous request
- [ ] 3.3 Verify cache hit → `X-Cache: hit` + `Age` header on repeat request
- [ ] 3.4 Verify `wordpress_logged_in_*` cookie bypasses cache
- [ ] 3.5 Verify `wp-admin/*` and `wp-login.php` bypass cache
- [ ] 3.6 Verify `curl -X PURGE` invalidates cache
