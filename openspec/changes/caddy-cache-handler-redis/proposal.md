# Proposal: Caddy Cache Handler

## Intent

Every anonymous page view hits full PHP + WordPress stack (~200ms). No HTTP-level response caching exists. Add Caddy Cache Handler (Badger-backed) to serve cached HTML in ~1ms on cache hits.

## Scope

### In Scope
- **Dockerfile**: Rebuild FrankenPHP with `caddyserver/cache-handler` module via XCADDY_ARGS
- **Caddyfile**: Global `cache {}` block (Badger storage, TTL 5min, stale 1min), per-site `cache` directive with cookie-based bypass for logged-in users

### Out of Scope
- Redis object cache — deferred
- `wp acorn optimize` (route/config caching) — deferred, fragile with FrankenPHP threading
- Cloudflare CDN — user decided against
- Early Hints (103) — deferred
- Cache invalidation on content publish — initial manual purge only (`PURGE` request via wp-cli)

## Capabilities

### New Capabilities
- `http-response-cache`: RFC 7234 HTTP cache at Caddy level serving anonymous HTML requests from Badger storage, with cookie-based bypass for authenticated sessions
- `redis-object-cache`: WordPress object cache backend using Redis, replacing SQLite for transients and query caches

### Modified Capabilities
- None

## Approach

1. **Dockerfile**: Add `FROM dunglas/frankenphp:php8.4-bookworm AS frankenphp-ext` builder stage. `ARG XCADDY_ARGS="--with github.com/caddyserver/cache-handler"`. Run `xcaddy build` with those args. Prod stage copies binary from builder. Add `install-php-extensions redis` to prod stage.
2. **Caddyfile**: Global block adds `cache` with Badger storage dir `/data/caddy/cache`, TTL `5m`, stale `1m`. Per-site `route /` applies `cache` before `php_server`. Bypass on `cookie` match for `wordpress_logged_in*` and `wp-*` paths.
3. **Redis provisioning**: Railway Redis template → `REDIS_URL` env var available to app service.
4. **WordPress config**: `config/application.php` sets `WP_REDIS_URL`, `WP_REDIS_PREFIX`, `WP_REDIS_DATABASE`. Add phpredis-backed drop-in.
5. **Local dev**: `docker-compose.yml` Redis service mirrors Railway setup.

## Affected Areas

| Area | Impact | Description |
|------|--------|-------------|
| `Dockerfile` | Modified | Add builder stage + redis extension |
| `Caddyfile` | Modified | Add cache block + bypass rules |
| `docker-compose.yml` | Modified | Add Redis service |
| `config/application.php` | Modified | Add WP_REDIS_* constants |
| `web/app/mu-plugins/` | New | Redis object cache drop-in |
| `composer.json` | Modified | Optional: predis/predis (fallback) |

## Risks

| Risk | Likelihood | Mitigation |
|------|------------|------------|
| FrankenPHP threaded mode + Redis ZTS compat | Medium | Test with phpredis; fall back to Predis |
| Cache serves stale content to editors | Medium | Bypass on `wordpress_logged_in` cookie |
| Railway Redis adds cost (~$5/mo) | Low | Acceptable; object cache benefit justifies |
| Cache Handler Badger disk growth | Low | Set max size; monitor in Railway |

## Rollback Plan

- **Caddy config**: Revert Caddyfile changes, rebuild Docker image without cache-handler
- **Redis**: Remove `WP_REDIS_*` constants from `config/application.php`, remove drop-in
- **Dockerfile**: Revert to previous image build (no XCADDY_ARGS, no redis ext)
- Full revert takes one Railway deploy

## Dependencies

- Railway Redis template (deploy via `railway search_templates "redis"`)
- `caddyserver/cache-handler` Go module (open source)

## Success Criteria

- [ ] Anonymous HTML response returns `Age` header and `X-Cache: hit` on repeat requests
- [ ] Logged-in users always bypass cache (no `X-Cache: hit`)
- [ ] `wp cache get` works against Redis (object cache is active)
- [ ] Admin `/wp-admin/` pages never cached
- [ ] Cache purges when content is published (manual `PURGE` or wp-cli integration)
- [ ] Local `docker compose up` includes Redis and cache handler works
