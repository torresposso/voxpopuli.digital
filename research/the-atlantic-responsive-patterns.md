# The Atlantic — Patrones de Diseño Responsive (Julio 2026)

**Fuente**: CSS de producción (`/_next/static/css/c652263e9c47b6ee.css`) + bundle JS + HTML textual.  
**Propósito**: Extraer patrones concretos replicables con Tailwind CSS en el tema voxpopuli.

---

## 1. Breakpoints (sistema de grid)

The Atlantic usa **4 breakpoints activos** más un contenedor max-width:

| Alias | CSS | Equivalente Tailwind | Uso |
|---|---|---|---|
| Mobile | `max-width: 575px` (reglas *inversas*) | `max-sm:` | Hamburguesa, layout 1-col |
| Tablet | `min-width: 576px` | `sm:` | Nav cambia, grids 2-col |
| Tablet-L | `min-width: 786px` | `md:` | Grids 2-col más anchos |
| Desktop | `min-width: 976px` | `lg:` (custom) | Nav completo 4-col grids |
| Desktop-Wide | `min-width: 1024px` | `xl:` | Grids horizontales |
| Max-container | `min-width: 1296px` / `max-width: 1280px` | `max-w-7xl` + padding | Layout wrapper |

**Importante**: 976px no es un breakpoint nativo de Tailwind. Habría que extenderlo en `tailwind.config.js`:
```js
screens: {
  'sm': '576px',
  'md': '786px',
  'lg': '976px',
  'xl': '1024px',
  '2xl': '1280px',
}
```

También usan `@media(max-width: 575px)` para reglas exclusivamente mobile (ej: `.Nav_visuallyHideOnMobile__N9bs2`).

---

## 2. Navegación principal

### Estructura sticky
```css
.Nav_root__HcZek {
    position: sticky; /* en tablet+ */
    top: 0;
    z-index: 5999999;
}
```
- Barra negra de 4px arriba (`::before { background: #000; height: 4px }`) solo en tablet+.
- Offset del nav: `--site-nav-offset: 62px` (body lo declara via CSS `:has()`).
- Container: `max-width: 1280px; margin: 0 auto; padding: 20px 16px` mobile → `padding: 9px 16px` tablet.

### Mobile (<576px)
- Hamburger button (animación "Hamburgers" de Jonathan Suh — 3 líneas → X roja).
- Menú expandido: `position: fixed; top: 0; left: 0; width: 100vw; height: 100dvh; overflow-y: auto; padding-bottom: 32px`.
- El logo "Big A" rojo (`fill: #e7131a`) aparece centrado; el wordmark completo se oculta.
- Links de "Popular", "Latest", "Newsletters" aparecen en el header del menú expandido.
- Las secciones del menú usan `column-count: 2`.
- Overlay semitransparente: `background: #000; opacity: 0.35`.

### Tablet (576–975px)
- Hamburguesa sigue visible.
- Logo wordmark aparece (136×21px).
- Menú expandido: grid 2 columnas, secciones con `column-count: 3`.
- `Nav_root__HcZek` sticky con barra negra superior.

### Desktop (≥976px)
- Hamburguesa se oculta (`display: none` via `.Nav_hideOnTablet__wyFPd`).
- Logo grande: `width: 175px; height: 27px`.
- Nav horizontal clásico con items: (Big A logo) | Hamburguesa+Search | Popular | Latest | Newsletters | (Sign In / Subscribe).
- Subscribe button: `border: 1px solid #d0021b; border-radius: 4px; background: #e7131a; color: #fff; padding: 10px 18px 11px`.

### Nav items hiding por breakpoint
| Item | Mobile | Tablet | Desktop |
|---|---|---|---|
| Big A logo (in left) | hidden | hidden | visible |
| Popular link | visible | visible | visible |
| Latest link | visible | visible | visible |
| Newsletters link | visible | visible | visible |
| Saved Stories | hidden | hidden (>625px) | visible |
| Games | hidden | hidden (>1000px) | visible |

### Cómo replicarlo en Tailwind
```blade
<nav class="sticky top-0 z-[5999999] before:block before:h-1 before:bg-black max-sm:before:hidden">
  <div class="mx-auto max-w-7xl px-4 sm:px-4 sm:py-2.5 py-5 flex items-center justify-between">
    <!-- left: hamburger + logo -->
    <!-- center: wordmark (hidden on mobile) -->
    <!-- right: account links -->
  </div>
</nav>
```

---

## 3. Story Grid — `HorizontalRiver`

### Comportamiento responsive

| Viewport | Columnas | Filas | Gap |
|---|---|---|---|
| <768px | 1 | 4 | `row-gap: 40px` |
| 768-1023px | 2 | 2 | `row-gap: 32px` |
| ≥1024px | 4 | 1 | `column-gap: 32px` |

```css
.HorizontalRiver_ol__uYXOl {
    display: grid;
    grid-row-gap: 40px;
}
@media (min-width: 768px) {
    .HorizontalRiver_ol__uYXOl {
        grid-template-columns: repeat(2, 1fr);
        grid-template-rows: repeat(2, 1fr);
        row-gap: 32px;
    }
}
@media (min-width: 1024px) {
    .HorizontalRiver_ol__uYXOl {
        grid-template-columns: repeat(4, 1fr);
        grid-template-rows: 1fr;
        column-gap: 32px;
    }
}
```

### Divisores entre items
- **Mobile (<768px)**: Los primeros 3 items tienen `::after` con `height: 0.5px; background: rgba(0,0,0,0.4); bottom: -20px; width: 100%`.
- **Tablet (768-1023px)**: Items pares tienen línea vertical derecha (`::after` con `width: 0.5px; height: 100%`). Los primeros 2 items tienen línea horizontal inferior.
- **Desktop (≥1024px)**: Líneas verticales entre columnas (`::after` en los primeros 3 items, `right: -16px; width: 0.5px; height: 100%`).

### Imágenes en el grid
```css
.HorizontalRiver_picture__fLz63 img {
    width: 100%; /* aspect ratio natural — no forzado */
}
```

### Scroll horizontal (variante)
Para secciones como "Recommended For You":
```css
.HorizontalRiver_scrollContainer__EAvw0 {
    display: flex;
    overflow-x: scroll;
    padding-bottom: 20px;
    gap: 32px;
}
.HorizontalRiver_scrollItem__Y0yyg {
    width: 296px;
    flex-shrink: 0;
    max-width: calc(100vw - 48px);
}
```
Scroll snapping: primer item con `margin-left: 16px`, último con `margin-right: 16px`.  
En pantallas ≥1296px: márgenes se calculan como `calc((100vw - 1280px) / 2)`.

### Cómo replicarlo en Tailwind
```blade
<ol class="grid grid-cols-1 md:grid-cols-2 md:grid-rows-2 xl:grid-cols-4 xl:grid-rows-1 gap-y-10 md:gap-y-8 xl:gap-x-8">
  @foreach($items as $item)
    <li class="relative [&:nth-child(-n+3)]:after:block [&:nth-child(-n+3)]:after:absolute [&:nth-child(-n+3)]:after:bottom-[-20px] [&:nth-child(-n+3)]:after:w-full [&:nth-child(-n+3)]:after:h-[0.5px] [&:nth-child(-n+3)]:after:bg-black/40 md:[&:nth-child(-n+3)]:after:hidden xl:[&:nth-child(-n+3)]:after:block xl:[&:nth-child(-n+3)]:after:right-[-16px] xl:[&:nth-child(-n+3)]:after:bottom-auto xl:[&:nth-child(-n+3)]:after:w-[0.5px] xl:[&:nth-child(-n+3)]:after:h-full">
      <article class="flex flex-col h-full">
        <a href="{{ $item->url }}">
          <picture>
            <img src="{{ $item->image }}" loading="lazy" class="w-full" />
          </picture>
        </a>
        <h3 class="font-serif text-2xl leading-[1.5] mt-4">
          <a href="{{ $item->url }}">{{ $item->title }}</a>
        </h3>
        @if($item->dek)
          <div class="text-lg leading-[26px] mt-1">{!! $item->dek !!}</div>
        @endif
      </article>
    </li>
  @endforeach
</ol>
```

---

## 4. Imágenes

### Componente `Image` (`Image_root__XxsOp`)

Atributos y comportamiento:
```css
.Image_root__XxsOp {
    width: 100%;
    height: auto;
    display: block;
}
.Image_lazy__hYWHV {
    opacity: 0;
}
.Image_loaded__zmzJ7 {
    opacity: 1;
    transition: opacity 0.3s;
}
```

- Usan `<img loading="lazy">` nativo para lazy loading.
- Manejan estado `loaded` vía JavaScript (opacity 0→1 con fade de 300ms).
- **Preload**: para imágenes above-the-fold, insertan `<link rel="preload" as="image" href="..." imageSrcSet="..." imageSizes="...">` en el `<head>`.

### `srcSet` con múltiples tamaños
Del fragmento GraphQL de la 404:
```
sizes: ["186w", "273w", "290w", "309w", "328w", "334w", "372w", "378w", "546w", "580w", "618w", "656w", "668w", "756w"]
```

### `sizes` attribute usado:
```
"(min-width: 2560px) 334px, (min-width: 1440px) 290px, (min-width: 1024px) 186px, (min-width: 768px) 309px, (min-width) calc(100vw - 32px)"
```

### Reduced motion
Usan `<source media="(prefers-reduced-motion)" srcSet="...">` para reemplazar imágenes con animación por versiones estáticas.

### Cómo replicarlo en Tailwind
```blade
<img
  src="{{ $src }}"
  srcset="{{ $srcset }}"
  sizes="(min-width: 1024px) 186px, (min-width: 768px) 309px, calc(100vw - 32px)"
  loading="lazy"
  class="w-full h-auto block opacity-0 transition-opacity duration-300"
  onload="this.classList.remove('opacity-0')"
  alt="{{ $alt }}"
/>
```

---

## 5. Tipografía responsive

**No usan `clamp()`**. Usan `font-size` fijo con media queries:

| Elemento | Mobile | Desktop | Font-family |
|---|---|---|---|
| Headline (River) | `24px / 1.5` | same | AGaramondPro |
| Dek (River) | `18px / 26px` | same | AGaramondPro |
| Nav links | `12px` | `14px` | Graphik |
| Nav logo | `height: 18px` | `175x27px` | SVG |
| Attribution/time | `11-12px` | same | Logic Monospace |
| Sections title (nav) | `14px / 44px` | same | Graphik |
| Quick Links heading | `12px / 12px` | `12px / 14px` (Logic Mono) | Graphik → Logic Mono |

### Stack de fuentes
```css
/* Serif para headlines */
font-family: AGaramondPro, "Adobe Garamond Pro", garamond, Times, serif;

/* Sans-serif para nav/UI */
font-family: Graphik, -apple-system, blinkmacsystemfont, roboto, "helvetica neue", segoe ui, arial, sans-serif;

/* Mono para metadata (autores, timestamps, etiquetas) */
font-family: "Logic Monospace", monospace;

/* Display serif para títulos de sección (Most Popular) */
font-family: "Atlantic Serif", Atlantic, Bodoni, Times, serif;
```

### Cómo replicarlo en Tailwind
```js
// tailwind.config.js
theme: {
  extend: {
    fontFamily: {
      serif: ['AGaramondPro', 'Adobe Garamond Pro', 'garamond', 'Times', 'serif'],
      sans: ['Graphik', '-apple-system', 'BlinkMacSystemFont', 'roboto', 'helvetica neue', 'segoe ui', 'arial', 'sans-serif'],
      mono: ['Logic Monospace', 'monospace'],
      display: ['Atlantic Serif', 'Atlantic', 'Bodoni', 'Times', 'serif'],
    },
    fontSize: {
      'river-title': ['24px', '1.5'],
      'river-dek': ['18px', '26px'],
    },
  },
}
```

---

## 6. Newsletter signup

**Inline** (no modal). Aparece como una sección más en el flujo de la página:

```
## Newsletters See All

The Atlantic Daily

Get our editors' guide to what matters in the world, delivered to your inbox every weekday and Sunday mornings.

[Email Address] [Sign Up]

Your newsletter subscriptions are subject to The Atlantic's Privacy Policy and Terms and Conditions.
```

- Usan reCAPTCHA v3 (`6Lc9Z7AUAAAAAEYS1dgAG2_6tT3KLqZQ1z4kbDRc`).
- POST a `https://accounts-api.theatlantic.com/api/v1/newsletters/sign-up/`.
- Sin clase CSS visible desde el bundle — probablemente sea HTML server-rendered sin estilos complejos.
- En el nav mobile aparece un link a Newsletters.

---

## 7. Sección "Latest" y "Popular"

### Latest
- Lista vertical simple, 1 columna.
- Cada item: título + autor + timestamp (formato "7:00 AM ET" o "July 14, 2026").
- Monospace para timestamps, sans-serif para títulos.
- Sin imágenes en esta sección.

### Popular
- Similar a Latest: lista vertical simple.
- Sin timestamps — solo título + autor.
- Aparece como sidebar o sección independiente.

---

## 8. Secciones "Recommended For You" y "Archive"

### Recommended For You
- Usa el componente `HorizontalRiver` con **scroll horizontal**.
- 4 items en un contenedor `overflow-x: scroll` con `gap: 32px`.
- Items de 296px de ancho, `flex-shrink: 0`.
- Incluye imágenes con srcset.
- Tiene un encabezado "RECOMMENDED FOR YOU" con `border-top: 1px solid #000` + `text-align: right`.

### Archive
- Similar al river scroll pero con items más pequeños.
- Incluye poemas y contenido histórico.

---

## 9. Separación entre secciones

Usan **ambos**: líneas horizontales y whitespace.

| Técnica | Dónde | CSS |
|---|---|---|
| `border-top` | Encabezados de sección (scroll sections) | `border-top: 1px solid #000` |
| `::after` pseudo-elementos | Entre items del grid | `height: 0.5px; background: rgba(0,0,0,0.4)` |
| `<hr>` | En el nav expandido (print section) | `height: 1px; background: #000` |
| `row-gap` / `column-gap` | Entre filas/columnas del grid | `40px` mobile, `32px` tablet |
| `padding-bottom` | Entre secciones | `32px`, `48px`, `80px` |

### Cómo replicarlo en Tailwind
```blade
<section class="border-t border-black">
  <h2 class="font-mono text-sm uppercase tracking-wider text-right py-4">
    Recommended For You
  </h2>
  <!-- content -->
</section>
```

O para separar secciones con whitespace:
```blade
<section class="py-10 md:py-12">
  <!-- content -->
</section>
```

---

## 10. Footer (Quick Links)

El footer es un componente llamado `QuickLinks`:

### Layout responsive
```css
.QuickLinks_quickLinksList__e7x66 {
    display: block; /* mobile: vertical */
    padding: 0;
}
@media (min-width: 976px) {
    .QuickLinks_quickLinksList__e7x66 {
        display: inline-block; /* desktop: horizontal */
    }
}
```

### Items
```css
.QuickLinks_quickLinkListItem__59_09 {
    display: block;
    padding-bottom: 24px;
}
@media (min-width: 976px) {
    .QuickLinks_quickLinkListItem__59_09 {
        display: inline-block;
        margin: 0 24px 0 0;
        padding-bottom: 0;
    }
}
```

### Imágenes
- Mobile: `display: none` (no se muestran imágenes).
- Desktop: `display: block; width: 148px; height: 148px; object-fit: cover; border: 0.5px solid #d3dce6`.

### Estructura
```
Quick Links
- Audio (148x148 thumbnail + label)
- Crossword Puzzle (148x148 thumbnail + label)
- Magazine Archive (148x148 thumbnail + label)
- Your Subscription (148x148 thumbnail + label)
```

### Cómo replicarlo en Tailwind
```blade
<div class="pt-8 lg:pt-12">
  <h3 class="font-mono text-xs uppercase tracking-wider pb-8 lg:pb-8">Quick Links</h3>
  <ul class="lg:inline-block">
    @foreach($links as $link)
      <li class="block pb-6 lg:inline-block lg:pb-0 lg:mr-6">
        <a href="{{ $link->url }}" class="text-inherit no-underline">
          <img src="{{ $link->image }}" class="hidden lg:block w-[148px] h-[148px] object-cover border border-[#d3dce6]" />
          <span class="font-sans text-sm font-light leading-5 lg:pt-3 lg:font-mono lg:leading-6">{{ $link->label }}</span>
        </a>
      </li>
    @endforeach
  </ul>
</div>
```

---

## 11. Variables CSS

```css
:root {
    --nav-collapsed-background: #fff;
    --nav-expanded-background: #f7f7f7;
    --nav-expanded-background-accent: #fff;
    --nav-border-color-dark: #9b9b9b;
    --nav-border-color-light: #c1c1c1;
    --paywall-background: #fff;
    --article-image-loading-placeholder-color: #c0ccda;
    --article-sharekit-background: #f7f7f7;
}
```

Color rojo distintivo: `#e7131a` (uso en logo, subscribe button, nav section titles).

---

## 12. Resumen de patrones clave para replicar

| Patrón | Mobile | Tablet (≥576) | Desktop (≥976/1024) |
|---|---|---|---|
| Nav | Hamburguesa + Big A logo | Hamburguesa + wordmark | Nav horizontal completo |
| Story Grid | 1 col | 2 cols | 4 cols |
| Scroll sections | Scroll horizontal 296px items | same | same |
| Footer links | Vertical, sin imágenes | Vertical, sin imágenes | Horizontal, con thumbnails 148×148 |
| Containers | Padding 16px | Padding 16px o 32px | `max-width: 1280px` + padding 0 |
| Subscribe btn | Text link rojo | Text link rojo | Botón rojo con border |
| Nav height | 62px (contenido) | 62px | 62px |
| Section separators | `::after` 0.5px entre items | `border-top` + pseudo | `::after` vertical entre cols |

### Lo que NO hacen (y deberíamos considerar)
1. **No usan `clamp()`** para tipografía fluida — usan tamaños fijos por breakpoint.
2. **No tienen hero full-bleed** en sentido tradicional (su homepage empieza directo con el story grid; la "hero" es la primera story del grid).
3. **Las imágenes no fuerzan aspect ratio** fijo — dejan que `width: 100%; height: auto` mantenga el ratio natural.
4. **No hay dark mode** — solo tema claro.
