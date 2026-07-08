# Tasks: acta-populi Theme

Decision needed before apply: Yes
Chained PRs recommended: Yes
Chain strategy: pending
400-line budget risk: High

## Work Units
| # | Goal | Base | Est. |
|---|------|------|------|
| 1 | Scaffold + ports (dir, config, providers, SEO) | feature/tracker | ~600 |
| 2 | CSS + layout + 15 components + Hero.php | PR 1 | ~700 |
| 3 | 4 composers + TemplateServiceProvider + 6 templates | PR 2 | ~700 |
| 4 | 5 JS modules + tests + lint + screenshot + activate | PR 3 → main | ~400 |

## Phase 1: Scaffolding

- [x] 1.1 Create `acta-populi/` dir tree; write composer.json, functions.php, style.css, theme.json
- [x] 1.2 Write package.json, vite.config.js, Vite.php (port from voxpopuli)
- [x] 1.3 Write config files: app, assets, view, database, filesystems (port + adjust paths)
- [x] 1.4 Port filters.php, Security.php, Theme.php (namespace update only)
- [x] 1.5 Port all 5 Providers (update cache prefixes to acta-populi_*)
- [x] 1.6 Port all 5 SEO classes (JsonLd, SeoMeta, MetaRenderer, Migration, Sitemap)
- [x] 1.7 Run `composer install` + `npm install`, verify no errors

## Phase 2: CSS + Layout

- [ ] 2.1 Write app.css: `@import "tailwindcss"`, DaisyUI acta-populi theme, @tailwindcss/typography
- [ ] 2.2 Add Acta effects: parallax hero, nav scroll-snap, scroll-progress keyframes, drop cap, noise overlay, accent bars
- [ ] 2.3 Write app.blade.php: head with asset loading, drawer wrapper, utility-strip, sticky navbar, `@yield('content')`, footer, JS footer imports

## Phase 3: Components

- [ ] 3.1 Write structural components: wordmark, utility-strip, navbar, drawer, footer
- [ ] 3.2 Write Hero.php class + hero.blade.php (destacadas query, LCP eager image)
- [ ] 3.3 Write content components: post-card, post-card-compact, latest-grid, category-spotlight
- [ ] 3.4 Write supplementary: video-feature, newsletter, entry-meta, social-share, reading-progress

## Phase 4: Data Layer

- [ ] 4.1 Write Index.php composer (hero + 7 latest + 5 cat sections with Cache::remember)
- [ ] 4.2 Write Post.php, Archive.php, Seo.php composers
- [ ] 4.3 Write partials: seo-head.blade.php, content-single.blade.php
- [ ] 4.4 Adapt ThemeServiceProvider: register Blade components, nav menus, sidebars

## Phase 5: Templates

- [ ] 5.1 Write front-page.blade.php (orchestrate all 9 sections in order)
- [ ] 5.2 Write single.blade.php (reading progress, entry-meta, prose, social-share, sidebar)
- [ ] 5.3 Write archive.blade.php (category header, 3-col grid, DaisyUI pagination)
- [ ] 5.4 Write search.blade.php (query header, result grid, empty state)
- [ ] 5.5 Write page.blade.php (centered max-w 48rem prose) + 404.blade.php (editorial styled error)

## Phase 6: JavaScript

- [ ] 6.1 Write 5 JS modules: search-overlay, drawer, scroll-progress, video-feature, nav-tabs
- [ ] 6.2 Write app.js entry: import all modules, init on DOMContentLoaded, destroy on cleanup

## Phase 7: Testing

- [ ] 7.1 Write Pest tests: providers bootstrap correctly (PRAGMAs, reading-time meta)
- [ ] 7.2 Write Pest tests: SEO classes output correct JsonLd + meta tags
- [ ] 7.3 Write Pest tests: Index composer data shape, Hero component null case
- [ ] 7.4 Run `composer lint:fix`, run full test suite, fix all failures
- [ ] 7.5 Add screenshot.png; verify responsive (375/768/1024); LCP check on hero image; add noise.png asset

## Phase 8: Activation

- [ ] 8.1 Commit all chained PRs; activate theme from WP Admin; verify front-end renders all sections
