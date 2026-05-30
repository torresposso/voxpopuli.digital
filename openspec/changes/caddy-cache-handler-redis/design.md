# Design: Caddy Cache Handler

## Technical Approach

Add a custom FrankenPHP builder stage that compiles the `cache-handler` Caddy module into the binary. The prod stage copies this custom binary and configures Badger-backed HTTP caching in Caddyfile. No Redis.

## Architecture Decisions

### Decision: Builder stage for custom FrankenPHP

**Choice**: Dedicated `frankenphp-ext` builder stage using `xcaddy`
**Alternatives**: Pre-built image, building from PHP source
**Rationale**: `xcaddy` is the official Caddy module builder. The builder stage produces a binary; prod stage copies it. Clean separation, no build tools in prod image.

### Decision: Badger embedded storage (no Redis)

**Choice**: Badger (embedded key-value store, file-based)
**Alternatives**: Redis, Etcd, NutsDB, Otter
**Rationale**: Zero external dependencies. Badger stores cache on disk at `/data/caddy/cache`. Survives Railway restarts via persistent volume. Good for single-server deployment.

### Decision: Cookie bypass via Caddy handle block

**Choice**: `handle @logged_in` block runs before cached route
**Alternatives**: Cache Handler's `regex { exclude }` for paths only
**Rationale**: Cache Handler module doesn't support cookie-based exclusion natively. A Caddy `handle` block with `header_regexp` cookie match + `reverse_proxy` to php_server bypasses the cache entirely for logged-in users.

## Data Flow

```
                         ┌─ Hit ──→ Response (Age, X-Cache: hit)
                         │
[Request] ─→ Caddy ──────┤
                  │      └─ Miss ─→ FrankenPHP ─→ WordPress ─→ SQLite
                  │                         │
            Cookie check?                    │
           (wordpress_logged_in)             │
                  │                         │
            Bypass ──────────────────────────┘
                                       Response ─→ Badger store ─→ Response
```

## File Changes

| File | Action | Description |
|------|--------|-------------|
| `Dockerfile` | Modify | Add `frankenphp-ext` builder stage with cache-handler; prod copies binary |
| `Caddyfile` | Modify | Add `cache {}` global block, cookie bypass, route with cache directive |
| `docker-compose.yml` | Modify | Remove Redis (not needed) |

## Testing Strategy

| Layer | What to Test | How |
|-------|-------------|-----|
| Cache hit | Anonymous HTML request | `curl` first request (miss), second request (hit), verify `X-Cache` headers |
| Cache bypass | Logged-in cookie | Request with `wordpress_logged_in_*` cookie, verify no `X-Cache: hit` |
| Cache bypass | Admin paths | `GET /wp-admin/*` and `/wp-login.php`, verify no cache |
| Cache purge | PURGE request | `curl -X PURGE`, verify next request is miss |

## Migration / Rollout

No migration required. Deploy triggers Railway build with new Docker image. Caddyfile loads on container start.

## Open Questions

None.
