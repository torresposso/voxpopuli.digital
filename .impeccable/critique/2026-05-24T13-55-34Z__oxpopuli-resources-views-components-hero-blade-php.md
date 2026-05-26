---
target: Typography Audit
total_score: 16.3
p0_count: 0
p1_count: 3
timestamp: 2026-05-24T13-55-34Z
slug: oxpopuli-resources-views-components-hero-blade-php
---
# Typography Audit: Vox Populi Digital Layouts
Target: `web/app/themes/voxpopuli/resources/views/components/`

#### Audit Health Score

| # | Dimension | Score | Key Finding |
|---|-----------|-------|-------------|
| 1 | Accessibility | 3.8/4 | Slashes and separators aria-hidden, kickers are readable. |
| 2 | Performance | 4/4 | System-defined font weights render cleanly without synthesis. |
| 3 | Responsive Design | 3/4 | Main featured title is too small (`text-xl md:text-3xl`) on high-res. |
| 4 | Theming | 2.5/4 | Wordmark uses wrong font token; excerpts use wrong font family. |
| 5 | Anti-Patterns | 3/4 | Sans font for article excerpts cheapens the editorial tone. |
| **Total** | | **16.3/20** | **Good (Address weak dimensions)** |

---

### Anti-Patterns Verdict
**PASS WITH OBSERVATIONS**: While the typography hierarchy is solid and clean of typical AI-bloated sizes, it carries three critical token mismatches that dilute the bespoke identity of a premium Caribbean digital magazine. The most notable tell is the use of a generic sans font for article excerpts, making it feel like a standard SaaS blog rather than a high-end publication.

---

### Executive Summary
* Audit Health Score: **16.3/20 (Good)**.
* Total issues found: **3 major (P1), 2 minor (P2)**.
* **Top 3 Critical Issues**:
  1. The **Wordmark** logo uses `font-sans` (Plus Jakarta Sans) instead of the brand's premium `font-display` (Playfair Display).
  2. The **Article Excerpt** uses `font-sans` (Plus Jakarta Sans) instead of the literary `font-serif` (Literata).
  3. The **Main Title** size in the Hero is too small for high-resolution desktop viewports, reducing frontpage authority.

---

### Detailed Findings by Severity

#### [P1] Wordmark Font Family Mismatch
* **Location**: `wordmark.blade.php`, line 5
* **Category**: Theming / Anti-Pattern
* **Impact**: Dilutes the brand's visual identity. The logo looks like a geometric UI element instead of an elegant, bespoke typographic wordmark in Playfair Display.
* **Recommendation**: Change `font-sans` to `font-display` in `wordmark.blade.php`.
* **Suggested command**: `/impeccable typeset`

#### [P1] Excerpt Font Family Mismatch
* **Location**: `hero.blade.php`, line 60
* **Category**: Theming / Anti-Pattern
* **Impact**: Lowers the literary and editorial prestige of the main story, giving it a dry "tech blog" appearance.
* **Recommendation**: Change `font-sans` to `font-serif` (`Literata`) on the excerpt paragraph.
* **Suggested command**: `/impeccable typeset`

#### [P1] Main Headline Size Under-Scaled
* **Location**: `hero.blade.php`, line 52
* **Category**: Responsive Design / Hierarchy
* **Impact**: Reduces visual impact and fails to command attention on desktop viewports.
* **Recommendation**: Scale the main featured headline to `text-2xl md:text-4xl lg:text-[2.75rem] md:leading-[1.05]`.
* **Suggested command**: `/impeccable layout`

#### [P2] Navbar Font Weight Over-Specification
* **Location**: `navbar.blade.php`, line 10
* **Category**: Theming
* **Impact**: Uses `font-black` (900) for Plus Jakarta Sans, which only has an 800 (Extrabold) token. Forces the browser to synthetically render or fallback.
* **Recommendation**: Update `font-black` to `font-extrabold` in navigation links.
* **Suggested command**: `/impeccable polish`

#### [P2] Sidebar pulsating dot description
* **Location**: `hero.blade.php`, line 128
* **Category**: Accessibility
* **Impact**: The pulsating orange dot is decorative but might confuse screen readers if not marked.
* **Recommendation**: Ensure the dot has `aria-hidden="true"`.
* **Suggested command**: `/impeccable polish`

---

### Positive Findings
- Kickers are perfectly tokenized in `font-sans font-extrabold text-[10px] tracking-[0.2em] text-secondary` with proper `aria-hidden` tags.
- Metadata perfectly follows the `font-sans text-xs text-neutral font-semibold tracking-wider` specifications.
- Deep, highly contrastive text scales mapped directly to the OKLCH system.
