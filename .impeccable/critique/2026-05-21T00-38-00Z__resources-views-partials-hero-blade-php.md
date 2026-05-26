---
target: frontpage hero
total_score: 26
p0_count: 0
p1_count: 3
p2_count: 2
timestamp: 2026-05-21T00-38-00Z
slug: resources-views-partials-hero-blade-php
---
#### Design Health Score

| # | Heuristic | Score | Key Issue |
|---|-----------|-------|-----------|
| 1 | Visibility of System Status | 3 | Dot indicators show position; missing loading/skeleton state while images load. Dead gradient transitions provide no feedback on hover. |
| 2 | Match System / Real World | 4 | Editorial conventions nailed. Natural news-reading flow. Caribbean identity through purple canvas, not cliché. |
| 3 | User Control and Freedom | 3 | Tap → article, back returns. Mobile carousel swipe freedom. Missing: no "view all headlines" escape from carousel. Dots are non-functional as navigation. |
| 4 | Consistency and Standards | 3 | Card structure consistent. Mobile vs desktop diverge intentionally. The `text-[10px]` used 11× vs having a token is a standards miss. |
| 5 | Error Prevention | 2 | No destructive actions. But CSS background images have zero fallback on load failure — blank purple panel with no indication. |
| 6 | Recognition Rather Than Recall | 4 | Everything visible: titles, excerpts, categories, dates. Zero hidden menus. |
| 7 | Flexibility and Efficiency | 1 | No shortcuts, no "skip to latest", no alternate views. Not critical for hero, but a "view all" link would help power users. |
| 8 | Aesthetic and Minimalist Design | 3 | Clean, purposeful. Drenched purple is bold and distinctive. Noise overlay + gradient layers risk visual busyness on complex images. |
| 9 | Error Recovery | 2 | Zero-post state is excellent. Image load failures on `background-image` divs are silent and unrecoverable. |
| 10 | Help and Documentation | 1 | No help — expected for a hero. Score reflects context. |
| **Total** | | **26/40** | **Acceptable** |

#### Anti-Patterns Verdict

**LLM assessment**: PASS — A real designer built this. The asymmetric grid, noise texture, Drenched purple strategy, varied card treatments are decisions, not defaults. No one looks at this and says "AI made it." Playfair Display is on the reflex-reject list, but it's grandfathered as existing brand identity for a literal editorial publication — not a borrowed costume.

The grayscale→color reveal on every single desktop image (including the primary 2×2 hero) borders on defensive design habit rather than signature move. The NYT uses it selectively. Here it drains the Caribbean color from the most prominent visual on the page.

**Deterministic scan**: 27 issues found (20 actionable after false positives). Categories: A11y (9), Perf (5), Theming (4), Responsive (4), Anti-pattern (5). Most critical systemic finding: CSS `background-image` for all content images — disables alt text, blocks lazy loading, prevents `srcset`.

**Browser verification**: Confirmed — dot clicks do nothing, gradient hover transitions have zero visual effect (`via-black/30` is not animatable), images load at full resolution, carousel JS selectors don't match HTML (dead code in `app.js`).

#### Overall Impression

The hero is the strongest part of Vox Populi's frontend — the Drenched purple canvas and asymmetric grid show editorial design thinking at a level most small news sites never reach. But it has two fundamental problems that undermine its purpose:

1. **Mobile readers see only ONE article at a time** in a carousel that hides the rest. The second-most-important story of the day is invisible.
2. **Every content image is a CSS `background-image`** — inaccessible, unlazyable, unresponsive. The core visual journalism of the site is invisible to screen readers and unoptimized for Maria's limited data.

Fix those two things and this hero goes from good to exceptional.

#### What's Working

1. **Drenched purple canvas is an identity-forming move.** Most news heroes are white. This deep purple signals: this is not generic journalism. It's Caribbean, serious, independent. The orange secondary accents are applied with precision — ~10% of the surface. The noise overlay adds tactile grit.

2. **The asymmetric desktop grid is genuinely well-composed.** `col-span-2 row-span-2` primary, stacked secondary, full-width strip for the 4th post. Each gets a visually distinct treatment. The bottom-strip with its horizontal orientation and "Leer →" CTA is a clever editorial break.

3. **Zero-posts fallback is more polished than many primary states.** Wordmark with orange accent, confident tagline, dual CTAs, gradient overlay. This state received design attention, not an afterthought.

#### Priority Issues

##### P1 — CSS `background-image` for all content images is a critical a11y + perf failure

**Location**: `hero.blade.php:17,74,108,142` — every post image.

**Why**: Screen readers cannot read `background-image`. Every hero photograph is invisible to blind users. Locks you out of `loading="lazy"`, `srcset`, `<picture>`, `alt` text. Full-resolution originals are fetched for every viewport.

**Fix**: Convert to `<img>` with `alt` (derived from post caption), `object-fit: cover`, `loading="lazy"`, and `srcset` for responsive sizes.

**Suggested command**: `impeccable harden hero`

##### P1 — Mobile carousel hides 75% of content behind a swipe

**Location**: `hero.blade.php:9-42`. Each slide is `w-[85vw]` — one article visible at a time.

**Why**: The hero's job is editorial hierarchy. On mobile (the primary consumption device), Carlos sees exactly ONE article. The second, third, and fourth most important picks are invisible. This contradicts the hero purpose: "make the editorial hierarchy evident without requiring interaction."

**Fix**: Show a peek of the next card (`w-[70vw]` with snap overlap), or restructure to 2-card vertical stack for first two posts. At minimum, make the second post partially visible.

**Suggested command**: `impeccable adapt hero mobile`

##### P1 — Dot navigation is broken

**Location**: `hero.blade.php:45-55` + `app.js:3-5`.

**Why**: Dots are `<a href="#slide-N">` but no element with `id="slide-N"` exists. Clicking does nothing. The JS that updates `aria-selected` and active state queries `.md\\:hidden .carousel` (HTML has `<ul>` not `.carousel`) and `.gap-1\\.5 a` (HTML has `gap-2`). The update function is dead code — dots never change.

**Fix**: Add `id="slide-{{ $i }}"` to `<li>` elements and wire dots to scroll the carousel via `scrollIntoView`. Or fix the JS selectors: `.carousel` → `ul` and `.gap-1\\.5` → `.gap-2`. And add IntersectionObserver or scroll-based `aria-selected` updates.

**Suggested command**: `impeccable harden hero carousel`

##### P2 — Grayscale on ALL desktop hero images

**Location**: `app.css:76-78`, applied at `hero.blade.php:74,108,142`.

**Why**: The primary post (2×2, dominant) starts fully desaturated. Only reveals color on hover. The Caribbean is color — draining it on first impression says "this place is grey." Grayscale→color is a known pattern (NYT) but NYT uses it selectively, not on every single hero image.

**Fix**: Primary post loads in full color. Grayscale→color on hover for secondary posts only. Or start at 50% saturation.

**Suggested command**: `impeccable colorize hero`

##### P2 — No loading state for hero images

**Location**: `hero.blade.php` — no skeleton, no shimmer, no placeholder.

**Why**: On slow connections (Maria on 3G), the hero loads as a blank purple void while images download. `hero-enter` animation exists in CSS (lines 119-128) but is never applied in the template.

**Fix**: Apply `hero-enter` staggered entrance animation to hero content. Add CSS skeleton shimmer overlay on the image container.

**Suggested command**: `impeccable animate hero`

#### Persona Red Flags

**Carlos (Opinion Leader, 42, mobile)**:
- Sees only 1 of 3–4 top stories without swiping. If the first story isn't his interest, he closes the tab assuming nothing relevant exists.
- Dot navigation is broken. If he taps a dot expecting navigation, nothing happens. Loss of trust.
- Dots are 8px at `white/20` contrast — Carlos reading in sunlight on his phone can barely see them.

**Maria (Activist, 29, limited data)**:
- No `loading="lazy"` — full-resolution images load before Maria reads a single word. Her data budget is consumed before content.
- Blank purple screen for 3–5 seconds on 3G. No skeleton, no shimmer, no "cargando...".
- If images are disabled, Maria gets empty purple panels. The text is technically there but buried behind the assumption images exist.
- Zero alt text on any image (CSS backgrounds). If Maria uses assistive tech, the journalistic photographs are absent.

#### Minor Observations

1. `tracking-[0.2em]` vs `tracking-[0.15em]` vs `tracking-widest` — three tracking values for category labels. Audit for intentionality vs drift.
2. `hero-enter` keyframes defined in CSS (lines 119-128) but never used in the template. Either wire it up or delete dead code.
3. Secondary post excerpt hidden on mobile (`lg:block hidden` at line 159) while primary post shows it (line 37). Inconsistent information density.
4. `min-h-[75vh]` on mobile cards (line 14) is very tall. Hero dominates the viewport, pushing content below fold.
5. Badge "Destacado" uses `bg-primary` on the hero's own `bg-primary` background (line 26). `shadow-sm` provides subtle separation but is it enough?
6. Gradient hover transitions (`via-black/30`) are NOT animatable with CSS. The `transition-all duration-500` compiles but does nothing.
7. The mobile carousel JS in `app.js:3-5` queries `.md\\:hidden .carousel` — there is no `.carousel` class in the HTML. The entire carousel interaction JS is dead code.

#### Questions to Consider

1. The mobile carousel shows one article at a time and the dots are broken. Fix the dots, replace the carousel with a 2-card stack, or both? Which editorial tradeoff matters more: gesture familiarity or content visibility?
2. Grayscale→color on every hero image — is this a signature brand move, or a defensive design habit that drains Caribbean identity from first impression?
3. The `hero-enter` animation exists in CSS but isn't deployed. Intentional pause or unfinished work?
4. Would localizing the noise texture as a CSS `::before` pseudo-element (instead of 4 extra `<div>`s) reduce DOM complexity?
