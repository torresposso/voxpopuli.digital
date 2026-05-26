# Design System

## Theme

Vox Populi Digital — periodismo independiente desde el Caribe colombiano.

Tema claro por defecto. Modo oscuro no implementado. Color scheme: light.

## Colors

Todos los valores en OKLCH. Estrategia de color: **Restrained** (neutros teñidos + un acento primario dominante ~10%, secondary como acento secundario ~5%).

### Surface & Background

| Token | OKLCH | HEX | Uso |
|---|---|---|---|
| --color-base-100 | oklch(98.7% 0.002 240) | #fbfcfd | Fondo de página principal |
| --color-base-200 | oklch(96.5% 0.004 240) | #f2f6f8 | Fondos secundarios, tarjetas |
| --color-base-300 | oklch(91.5% 0.01 240) | #dfe6eb | Bordes, separadores |
| --color-base-content | oklch(8% 0.005 240) | #080c0f | Texto sobre fondos claros |

### Brand

| Token | OKLCH | HEX | Uso |
|---|---|---|---|
| --color-primary | oklch(34.9% .1206 281.85) | #332d76 | Texto principal, header, enlaces, barra featured. ~10% de la interfaz |
| --color-primary-content | oklch(98.7% 0.002 240) | #fbfcfd | Texto sobre primary |
| --color-secondary | oklch(71.87% .166 57.77) | #ef8519 | Categorías, hover, acentos, "Vox" en wordmark. ~5% de la interfaz |
| --color-secondary-content | oklch(98.7% 0.002 240) | #fbfcfd | Texto sobre secondary (badges sólidos) |
| --color-accent | oklch(46% 0.14 28) | #b94642 | Alertas, etiquetas especiales |

### Neutral / Muted

| Token | OKLCH | HEX | Uso |
|---|---|---|---|
| --color-muted | oklch(42% 0.007 240) | #50565a | Metadatos, fechas, texto secundario |
| --color-border | oklch(91.5% 0.01 240) | #dfe6eb | Bordes, líneas divisorias (coincide con base-300) |
| --color-neutral | oklch(42% 0.007 240) | #50565a | Neutral |
| --color-neutral-content | oklch(98.7% 0.002 240) | #fbfcfd | Texto sobre neutral |

### Semantic

| Token | OKLCH | HEX | Uso |
|---|---|---|---|
| --color-info | oklch(72% 0.16 225) | #00b2de | Notas informativas |
| --color-success | oklch(64% 0.17 140) | #3baa18 | Confirmaciones |
| --color-warning | oklch(78% 0.19 80) | #ffab00 | Avisos |
| --color-error | oklch(60% 0.26 29) | #ff2815 | Errores, alertas críticas |

### Contrast ratios (WCAG 2.1)

| Combinación | Ratio | Verdict |
|---|---|---|
| Primary sobre Base-100 | 9.5:1 | AAA |
| Secondary sobre Base-100 | 3.8:1 | Falla AA texto normal — usar solo en badges sólidos |
| Base-Content sobre Base-100 | 18:1 | AAA |
| Muted sobre Base-100 | 5.2:1 | AA |

## Typography

### Sistema Híbrido

| Rol | Fuente | Categoría | Pesos |
|---|---|---|---|
| Display / headlines | Playfair Display | Serif (Didona) | 400, 400i, 700, 900 |
| Body (artículos) | Literata | Serif (lectura digital) | 400, 400i |
| UI / labels / badges | Plus Jakarta Sans | Sans-serif geométrica | 800 (Extrabold exclusivo) |

### Hierarchy

| Elemento | Fuente | Peso | Tamaño | Tracking |
|---|---|---|---|---|
| Wordmark | Playfair Display | 800 | text-2xl → text-4xl | tracking-tighter |
| Hero title | Playfair Display | 800 | text-2xl → text-7xl | tracking-tighter |
| Article title | Playfair Display | 700 | text-base → text-lg | tracking-tight |
| Section heading (h2) | Playfair Display | 700 | text-xl → text-2xl | tracking-tight |
| Category label | Plus Jakarta Sans | 800 | text-[9px] → text-[10px] | tracking-[0.2em] |
| Article body | Literata | 400 | text-base (1rem) | normal |
| Metadata | Plus Jakarta Sans | 600 | text-xs (0.75rem) | normal |

Body line length: 65–75ch. Escala tipográfica con ratio ≥ 1.25 entre steps.

## Layout

- **Grid**: 1 col (mobile), 2 col (md), 3 col (lg) para grid de posts
- **Hero**: 2/3 + 1/3 split en desktop (col-span-2 + col-span-1)
- **Contenido**: sin container rígido — espaciado con padding
- **Header**: fixed con backdrop-blur-md, fondo semitransparente
- **Sidebar**: opcional, lateral en layouts con sidebar

## Spacing

Valores consistentes con Tailwind v4 default scale. Uso común: p-4 (base), p-6, p-8, gap-4, gap-6, mt-8.

## Borders & Radii

| Propiedad | Valor |
|---|---|
| --radius-selector | 0.25rem |
| --radius-field | 0.25rem |
| --radius-box | 0.5rem |
| --radius-hero | 1.5rem (rounded-3xl) |
| --border | 1px |

## Effects

| Propiedad | Valor |
|---|---|
| --depth | 1 (relieve sutil 3D) |
| --noise | 0 |
| shadow-xl | Hero featured card |
| shadow-lg | Secondary hero cards |
| backdrop-blur-md | Header |

## Distinctive Elements

- **Primary top bar**: línea horizontal 4px en Primary (#332d76) sobre artículo featured
- **Grayscale reveal**: imágenes en escala de grises → color al hover
- **Texture overlay**: patrón de ruido (p6.png) sobre imágenes de fondo en hero
- **Separator ".:"**: punto + dos puntos como separador decorativo en títulos
- **Ranking numbers**: grandes, Secondary con opacidad 30%, para listados

## Components

### Wordmark
```txt
Vox    ← Secondary (#ef8519) / Playfair Display 800
Populi ← Primary (#332d76) / Playfair Display 800
```
Stacked, two lines, leading-none, tracking-tighter, left-aligned (header) or centered (footer). En fondos oscuros: "Vox" en blanco, "Populi" en Secondary.

### Hero (Featured Post)
- Full background image con hero-overlay bg-black/60 → hover bg-black/40
- Badge "Destacado" en primary
- Title en Playfair Display 800, text-4xl → text-5xl
- Excerpt line-clamp-2
- CTA "Leer más" btn primary rounded-full
- Transición: duration-500 en overlay

### Article Card
- grid card con shadow
- Hover: grayscale → color en imagen
- Category label: Plus Jakarta Sans 800, uppercase, wide tracking

### Alert
- x-alert component con type (warning, etc.)
- DaisyUI alert styles

### Button
- btn-primary rounded-full (hero CTA)
- btn-ghost con border blanco/20 hover bg-white/20 (secondary CTA en hero)
- btn-sm / btn-md / btn-lg

### Badge
- badge-primary para "Destacado"
- Badges sólidos para taxonomías (secondary con texto white)

## Logo / Wordmark

El wordmark es tipográfico. No hay logo SVG/PNG independiente. "Vox" en Secondary, "Populi" en Primary, Playfair Display 800, tracking-tighter, leading-none, apilado.

Tamaño mínimo digital: 80px de ancho.

## Motion

- Transiciones suaves (duration-500) en overlays de hero
- Sin bounce, sin elastic
- No animar propiedades de layout CSS
- Ease-out exponencial donde se aplique motion

## Accessibility

- WCAG AA con metas AAA en texto principal
- Secondary (naranja) solo en badges sólidos para AA
- Skip to content link (sr-only focus:not-sr-only)
- Roles ARIA en navegación
- Mobile-first responsive
