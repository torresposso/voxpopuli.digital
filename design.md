# Design: Landing Page Components

## Technical Approach

Extract 6 brand-book sections into self-contained Blade partials (`resources/views/partials/brand-*.blade.php`) composed by a new Landing Page template (`template-landing.blade.php`). All interactivity is CSS-only: `details/summary` for expand/collapse, `:has()` for parent-aware styling, scroll-snap for mobile carousels, `@container` for component responsiveness. Zero JS added — the existing `prefers-reduced-motion` guard in `app.css` covers animation safety.

## Architecture Decisions

### Decision: Composition Pattern

| Option | Tradeoff | Decision |
|--------|----------|----------|
| `x-` Blade components with `@props` | Reusable but verbose for static content | Use for 6 brand sections |
| `@include` partials with inline data | Simpler, matches codebase convention | Use for template assembly |
| ACF flexible content blocks | Over-engineered for static brand content | Postpone to v2 |

**Rationale**: Follow existing pattern from `components/alert.blade.php` (`@props` + `$attributes->merge()`). Each brand component accepts optional props with brand-book defaults baked in, so they render standalone. The landing template composes them via `@include`.

### Decision: CSS-only Interactivity Matrix

| Pattern | Used In | Why |
|---------|---------|-----|
| `details/summary` | Brand Values, Personas (descriptions) | Native disclosure, accessible, zero JS |
| `:has()` | Persona cards (`.card:has(details[open])`) | Style parent when child expands |
| `scroll-snap` | Hero mobile carousel | Aligns with existing carousel pattern |
| `:target` | Hero pagination dots | Already in use by `partials/hero.blade.php` |
| `@container` | All grid components | Component-level responsiveness independent of viewport |

### Decision: Image Strategy

Follow existing pattern from `hero.blade.php` and `content.blade.php`: `div` with `background-image`, `role="img"`, `aria-label`. No `<img>` tags — eliminates JS for lazy loading/error handling. Texture overlay (`transparenttextures.com/patterns/p6.png`) applied as child `div` with `pointer-events: none`.

### Decision: Responsiveness — Container Queries Over Media Queries

| Tradeoff | Resolution |
|----------|------------|
| Media queries couple layout to viewport | Use `@container` queries inside each component |
| `container-type: inline-size` needed on parent | Set on `.brand-section` wrapper in the template |
| Browser support: ~93% global | Acceptable for this audience; no functional breakage if unsupported (falls back to single column) |

### Decision: State Management

All state is HTML-native or CSS-pseudo:
- `open` attribute on `<details>` elements
- `:target` for carousel slide navigation
- `:hover` / `:focus-within` for interaction feedback
- `:has()` for cross-element state reflection (no JS coordination needed)

### Decision: Animation Strategy

| Approach | Application |
|----------|-------------|
| `@keyframes fade-in-up` | Manifesto, Stats — on load |
| Staggered `animation-delay` (0.1s steps) | Values grid, Sections grid — visual hierarchy |
| No scroll-triggered animations | `animation-timeline: view()` is Chromium-only |
| `prefers-reduced-motion: reduce` guard | Already global in `app.css` base layer — no additional work needed |

## Data Flow

```
template-landing.blade.php
  │
  ├── @include('partials.brand-hero', [...data...])
  ├── @include('partials.brand-values', [...data...])
  ├── @include('partials.brand-sections', [...data...])
  ├── @include('partials.brand-personas', [...data...])
  ├── @include('partials.brand-manifesto')
  └── @include('partials.brand-stats', [...data...])
```

Data is hardcoded as PHP arrays in the template (brand-book content rarely changes). Each partial accepts an optional `$data` prop; if omitted, it falls back to default brand-book content baked into the component. WordPress API calls (`home_url()`, `get_template_directory_uri()`) remain in the template for asset paths.

## File Changes

| File | Action | Description |
|------|--------|-------------|
| `resources/views/partials/brand-hero.blade.php` | Create | Landing hero: wordmark + tagline + featured content area |
| `resources/views/partials/brand-values.blade.php` | Create | 5 values grid with `details/summary` expandable descriptions |
| `resources/views/partials/brand-sections.blade.php` | Create | 6 editorial section cards with `@container` grid |
| `resources/views/partials/brand-personas.blade.php` | Create | 3 persona cards with `:has()` active-state styling |
| `resources/views/partials/brand-manifesto.blade.php` | Create | Editorial manifesto block with entrance animation |
| `resources/views/partials/brand-stats.blade.php` | Create | Key metrics grid (articles, readers, years, investigations) |
| `resources/views/template-landing.blade.php` | Create | WordPress Page Template composing all 6 partials |
| `resources/css/app.css` | Modify | Add component styles under `@layer components`: container queries, details/summary reset, animation keyframes |
| `resources/views/index.blade.php` | No change | Blog loop stays untouched; landing is a separate page |

## Interfaces / Contracts

```blade
{{-- template-landing.blade.php — Page Template: "Landing Page" --}}
@extends('layouts.app')

@section('content')
  @include('partials.brand-hero')
  @include('partials.brand-values')
  @include('partials.brand-sections')
  @include('partials.brand-personas')
  @include('partials.brand-stats')
  @include('partials.brand-manifesto')
@endsection
```

Each partial follows this contract:
```blade
@props([
  'values' => [
    ['title' => 'Independencia', 'description' => '...', 'icon' => '...'],
    // ...
  ]
])

<section {{ $attributes->merge(['class' => 'brand-section']) }}>
  {{-- component markup --}}
</section>
```

## Testing Strategy

| Layer | What to Test | Approach |
|-------|-------------|----------|
| Build | Vite compilation succeeds | `npm run build` — no errors from new CSS or Blade |
| Visual | 6 components render with brand-book content | Load the Landing Page template in browser, check each section |
| Responsive | `@container` fallback on unsupported browsers | Resize viewport to < 768px, verify single-column fallback |
| Interactivity | `details/summary`, `:target`, scroll-snap | Manual: open/close values on mobile, navigate carousel dots |
| Accessibility | `role="img"`, `aria-label`, keyboard nav | Tab through all interactive elements; screen reader test |
| Performance | No JS added | Confirm 0 new JS bytes in Vite build output |
| Motion | `prefers-reduced-motion` | Enable OS reduce-motion setting, verify all animations suppressed |

## Migration / Rollout

No migration required. The landing template is a new WordPress Page Template — it must be assigned in the WP admin to a page. Existing blog index (`index.blade.php`) is untouched.

## Open Questions

- [ ] Should brand-* partials become proper `x-` Blade components instead of `@include` partials? (Pro: tighter API contract. Con: requires registering in Acorn service provider.)
- [ ] Stats data source: hardcode editorial projections or leave as `@props` for future ACF integration?
- [ ] Confirm texture overlay URL (`p6.png`) availability in production — it's an external CDN resource.
