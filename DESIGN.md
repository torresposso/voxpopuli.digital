---
name: VoxPopuli Digital
description: Periodismo independiente desde el Caribe colombiano — El registro independiente de los hechos, el análisis técnico del poder y la memoria crítica del Caribe.
colors:
  azul-caribe: oklch(35% 0.144 278.697)
  naranja-caribe: oklch(75% 0.183 55.934)
  base-100: oklch(100% 0 0)
  base-200: oklch(98% 0.005 272.314)
  base-300: oklch(96% 0.018 272.314)
  base-content: oklch(21% 0.006 285.885)
  neutral: oklch(14% 0.005 285.823)
  neutral-content: oklch(92% 0.004 286.32)
typography:
  display:
    fontFamily: "Plus Jakarta Sans, sans-serif"
    fontWeight: 800
    letterSpacing: "0"
  body:
    fontFamily: "Newsreader, serif"
    fontSize: "1.25rem"
    fontWeight: 400
    lineHeight: 1.75
  label:
    fontFamily: "Plus Jakarta Sans, sans-serif"
    fontWeight: 800
    letterSpacing: "0.2em"
rounded:
  selector: "1rem"
  field: "2rem"
  box: "1rem"
components:
  button-primary:
    backgroundColor: "{colors.azul-caribe}"
    textColor: "{colors.naranja-caribe}"
    rounded: "{rounded.field}"
    padding: "0.5rem 1.5rem"
  button-outline:
    backgroundColor: "transparent"
    textColor: "{colors.azul-caribe}"
    rounded: "{rounded.field}"
    padding: "0.5rem 1.5rem"
  badge-accent:
    backgroundColor: "transparent"
    textColor: "{colors.naranja-caribe}"
    padding: "0"
---

# Design System: VoxPopuli Digital

## 1. Overview

**Creative North Star: "La Mirada Caribe"**

VoxPopuli Digital se constituye visualmente como un repositorio documental riguroso, sobrio y profundamente arraigado en la identidad del Caribe colombiano. No es un medio genérico con piel tropical: es un archivo vivo que mira el poder desde la costa, con la autoridad de quien conoce el territorio y la serenidad de quien no necesita gritar para ser escuchado.

El sistema visual rechaza explícitamente el sensacionalismo gráfico, los adornos cosméticos del SaaS comercial, las paletas "cálidas por defecto" del diseño editorial prefabricado y cualquier estética que priorice el entretenimiento sobre la comprensión. La marca se comunica con la gravedad de un expediente judicial, la calidez de una crónica caribeña y la precisión de un paper académico.

**Key Characteristics:**
- Estructuras ortogonales limpias con separadores sutiles (bordes de 2px en base-300)
- Tipografía sans geométrica para titulares y UI, serif ergonómica para lectura prolongada
- Inversión cromática deliberada: `primary-content` y `accent-content` se espejan mutuamente para crear identidad sin un tercer color
- Maquetación intrínseca sin breakpoints fijos — layouts que se auto-ajustan al contexto
- Ausencia absoluta de animaciones estridentes, glassmorphism, gradientes decorativos o sombras innecesarias
- Mobile-first con prioridad en rendimiento para conexiones limitadas (3G/Edge en zonas rurales del Caribe)

## 2. Colors

La paleta se ancla en dos colores caribeños deliberadamente no obvios: un azul profundo que evoca el mar nocturno y la tinta de un archivo histórico, y un naranja que recuerda el atardecer costeño y la urgencia de la denuncia. El fondo es blanco puro — sin tinte "cálido" o "crema" que suavice la postura editorial.

### Primary
- **Azul Caribe** (oklch(35% 0.144 278.697)): Color institucional de VoxPopuli. Usado en fondos de secciones premium (manifiesto editorial, footer), botones primarios, texto "Populi" en el wordmark, y barras de navegación. No debe superar el 30% de la superficie visible en ninguna pantalla.

### Accent
- **Naranja del Caribe** (oklch(75% 0.183 55.934)): Acento de atención. Reservado para badges de categoría, texto "Vox" en el wordmark, metadatos de autor, hover states en enlaces y botones outline. Deliberadamente limitado al ~5% de la superficie. En badges sólidos (naranja sobre texto blanco) alcanza AA; nunca se usa para texto informativo largo sobre fondos claros.

### Neutral
- **Base-100** (oklch(100% 0 0)): Fondo principal de página. Blanco puro, sin tinte. Ocupa ~70% del espacio visual.
- **Base-200** (oklch(98% 0.005 272.314)): Fondos secundarios, tarjetas de artículo, superficies elevadas. Tinte azul imperceptible que diferencia sin distraer.
- **Base-300** (oklch(96% 0.018 272.314)): Bordes, separadores, líneas divisorias. El borde es de 2px — visible y estructural, no decorativo.
- **Base-Content** (oklch(21% 0.006 285.885)): Texto principal sobre fondos claros. Casi negro con un matiz azul mínimo para suavizar la lectura sin perder contraste. Ratio 13.5:1 contra Base-100 (AAA).
- **Neutral** (oklch(14% 0.005 285.823)): Muy oscuro, para fondos invertidos.
- **Neutral-Content** (oklch(92% 0.004 286.32)): Texto claro sobre fondos oscuros.

### Named Rules
**The Mirror Rule.** `primary-content` y `accent-content` son imágenes especulares: el contenido del primary es el color del accent, y el contenido del accent es el color del primary. Texto naranja sobre fondo azul, texto azul sobre fondo naranja. Esta inversión crea identidad reconocible sin introducir un tercer color.

**The 5% Accent Rule.** El Naranja del Caribe se reserva para categorías, interacciones y la "V" del wordmark. Jamás supera el 5% de la superficie visual. Su rareza es su poder.

## 3. Typography

**Display Font:** Plus Jakarta Sans (with system-ui, sans-serif fallback)
**Body Font:** Newsreader (with Georgia, serif fallback)
**Label Font:** Plus Jakarta Sans (with system-ui, sans-serif fallback)

**Character:** Sans geométrica para titulares y UI + serif ergonómica para lectura. Plus Jakarta Sans unifica toda la interfaz — titulares, navegación, badges — con una sola voz sans que transmite claridad documental y modernidad sobria. Newsreader (diseñada por Production Type) está optimizada para lectura digital prolongada, con pesos ópticos que se ajustan automáticamente al tamaño de texto.

### Hierarchy
- **Display** (800, clamp(2rem, 5vw, 2.75rem), line-height 1): Titulares de artículos destacados en portada. Plus Jakarta Sans. `tracking-tight`.
- **Headline** (700, 1.75rem, line-height 1.25): Encabezados de sección (h2). Plus Jakarta Sans. `tracking-tight`.
- **Title** (700, 1.5rem, line-height 1.25): Títulos de tarjetas de artículo. Plus Jakarta Sans. `tracking-tight`.
- **Body** (400, 1.25rem, line-height 1.75): Cuerpo de artículos flagship. Newsreader. Ancho máximo de 75ch (`max-w-prose`). Para notas breves ("Ahora"): 1rem manteniendo la misma familia.
- **Label** (800, 0.75rem, letter-spacing 0.2em, uppercase): Categorías, badges, botones, metadatos. Plus Jakarta Sans.

### Named Rules
**The Reading Ergonomics Rule.** Todo artículo flagship usa Newsreader a 20px con `leading-relaxed` (1.75), ancho máximo de 75 caracteres. Los pesos ópticos de Newsreader se ajustan automáticamente al tamaño, mejorando la legibilidad en pantallas pequeñas con luz ambiente intensa del trópico.

**The CLS Prevention Rule.** Contenedores de texto con carga asíncrona de fuentes deben declarar `font-size-adjust: from-font` para mitigar desplazamientos de layout durante el intercambio de la fuente web.

## 4. Elevation

Sistema plano por defecto. Las tarjetas y contenedores permanecen sin sombra en su estado de reposo, diferenciándose solo por color de fondo (Base-200) y borde sutil (Base-300, 2px). La profundidad se comunica mediante capas tonales, no mediante sombras.

Las sombras aparecen exclusivamente como respuesta a estados interactivos (hover), y son ambientales, difusas y extremadamente sutiles.

### Shadow Vocabulary
- **Hover Glow** (`box-shadow: 0 4px 20px oklch(0% 0 0 / 8%)`): Efecto sutil de elevación al hacer hover sobre tarjetas de artículo. Difuso, sin dirección marcada.

### Named Rules
**The Flat-At-Rest Rule.** Todas las superficies son planas en reposo. Las sombras aparecen únicamente en respuesta a estado (hover, focus). Si una sombra es visible sin interacción del usuario, es un error.

## 5. Components

### Buttons
- **Shape:** Full pill (`border-radius: 2rem`). Bordes suaves que contrastan con la rigidez ortogonal del layout.
- **Primary:** Fondo Azul Caribe, texto Naranja del Caribe (Mirror Rule). Padding `0.5rem 1.5rem`. Label en Plus Jakarta Sans 800, uppercase, tracking 0.1em. Hover: el fondo se oscurece ligeramente. Focus-visible: outline Azul Caribe con offset de 4px.
- **Outline:** Borde Azul Caribe (2px), texto Azul Caribe, fondo transparente. Misma forma y padding que primary. Hover: fondo Azul Caribe, texto Naranja del Caribe. Usado en CTAs secundarios y el botón "Leer expediente" en el hero.

### Badges
- **Category Badge:** Texto Naranja del Caribe sobre fondo transparente. Sin borde, sin padding horizontal. Plus Jakarta Sans 800, 0.75rem, uppercase, tracking 0.2em. Usado sobre tarjetas y en el hero para identificar la sección editorial.
- **Solid Badge (invertido):** Fondo Naranja del Caribe, texto Base-100. Para estados seleccionados o destacados especiales. El único contexto donde el naranja cubre superficie.

### Cards
- **Shape:** Esquinas redondeadas (1rem). Fondo Base-200, borde Base-300 de 2px.
- **Anatomy:** Padding interno 1.5rem. Badge de categoría arriba, título en Plus Jakarta Sans 700, metadatos (autor en Naranja del Caribe + fecha en Neutral) abajo.
- **States:** Hover — sombra suave (Hover Glow), título transiciona a Naranja del Caribe en 200ms ease-out.

### Wordmark
El wordmark es puramente tipográfico — no hay logo SVG/PNG. La tipografía ES la marca.

**Wordmark canónico (inline):** `Vox Populi` en una línea. Plus Jakarta Sans 800, tracking normal (0). Hair space (0.15em) de separación entre palabras. `Vox` en Naranja del Caribe, `Populi` en Azul Caribe. Subtexto `digital` en Plus Jakarta Sans 400, Azul Caribe, tracking 0.15em, centrado bajo el wordmark completo, a ~40% del tamaño del wordmark. El subtexto se omite en espacios reducidos (header mobile, watermark).

**Wordmark stacked (variante autorizada):** `Vox` sobre `Populi` en dos líneas, `leading-none`. Para footer centrado, página "Nosotros" y contextos editoriales de firma institucional.

**Inversión sobre fondo oscuro** (Azul Caribe, Neutral, cualquier fondo L < 40%): `Vox` → blanco puro (Base-100), `Populi` → Naranja del Caribe. `digital` → blanco puro. La alternancia claro/color preserva la identidad en ambos contextos.

**Monogram VP:** Para favicon, app icon, redes sociales y contextos sub-48px. `V` en Naranja del Caribe, `P` en Azul Caribe. Tipográfico puro (sin contenedor) cuando el contexto ya provee un marco; dentro de contenedor blanco con bordes redondeados (1rem) cuando el fondo no tiene marco propio.

**Clearspace mínimo:** 0.5× la altura de la V mayúscula en los cuatro lados del wordmark. Tamaño mínimo digital: 80px de ancho para el wordmark canónico.

### Navigation (Drawer)
- **Header:** Barra fija superior con fondo Base-100, borde inferior Base-300 (2px). Wordmark a la izquierda. Menú horizontal en Plus Jakarta Sans 800, uppercase, tracking wider, oculto en mobile. Hamburguesa (drawer toggle) visible solo en `lg:hidden`.
- **Drawer Sidebar:** Panel lateral de 320px (w-80) con fondo Base-100, borde derecho Base-300. Overlay semitransparente al abrir. Wordmark en cabecera. Ítems de menú en Plus Jakarta Sans 600, 0.875rem.
- **Footer:** Fondo Azul Caribe, texto Naranja del Caribe. Wordmark centrado con "Vox" en blanco, "Populi" en Naranja del Caribe. Padding 2rem. Borde superior Base-300.

### Hero (Featured Post)
- **Layout:** Grid 12 columnas. Imagen 7/12 (aspecto 16:10, bordes redondeados 0.5rem), contenido 5/12. En mobile: single column, imagen arriba.
- **Imagen:** Transición de escala sutil al hover (`scale-102`, 500ms ease-out). Sin overlay decorativo.
- **Contenido:** Badge de categoría, título en Playfair Display 800 (clamp 2rem → 2.75rem), excerpt en Literata (line-clamp-3), metadatos + botón "Leer expediente" (outline).
- **Sin imágenes:** Placeholder con texto "Sin imagen documental" en Neutral sobre Base-200 — sin íconos decorativos ni gradientes de fondo.

### Manifiesto Editorial
- **Fondo:** Azul Caribe sólido (sin textura ni gradiente). Texto Naranja del Caribe (Mirror Rule).
- **Estructura:** Centrado, ancho máximo 45rem. Badge "Manifiesto Editorial" (Naranja sobre Azul), cita en Playfair Display italic (1.5rem → 2rem), firma con los tres valores ("Independencia. Rigor. Caribeidad.") en Plus Jakarta Sans.
- Es la única sección donde el color primario cubre una superficie grande deliberadamente — el resto del sitio mantiene fondo blanco.

## 6. Do's and Don'ts

### Do:
- **Do** limitar el ancho del cuerpo de lectura a 75ch máximo con `max-w-prose` para prevenir fatiga visual en lectura móvil.
- **Do** usar `font-size-adjust: from-font` en contenedores de texto para prevenir CLS durante la carga de Google Fonts.
- **Do** mantener el fondo Base-100 (blanco puro) como el color mayoritario (~70%) del espacio visual.
- **Do** aplicar la Mirror Rule consistentemente: texto accent sobre fondo primary, texto primary sobre fondo accent.
- **Do** usar bordes de 2px en Base-300 como separadores estructurales — son visibles, deliberados, no decorativos.
- **Do** priorizar layouts intrínsecos (Stack, Switcher, Cluster) sobre breakpoints fijos para adaptación contextual.

### Don't:
- **Don't** usar el Naranja del Caribe para texto informativo largo sobre fondos claros — solo en badges, hover states y el wordmark.
- **Don't** aplicar `border-left` o `border-right` mayor a 1px como acento decorativo en tarjetas o callouts.
- **Don't** combinar fondos degradados con `background-clip: text` para titulares — el texto siempre es color sólido.
- **Don't** usar modales (pop-ups, overlays) para flujos que puedan resolverse inline o al final del artículo. La conversión es post-lectura, nunca interruptiva.
- **Don't** aplicar filtros de escala de grises (grayscale) persistentes sobre rostros humanos en reportajes de derechos humanos o tragedias territoriales.
- **Don't** usar paletas "cálidas por defecto" (crema, arena, parchment) — el fondo es blanco puro, la calidez viene del contenido, no del tinte.
- **Don't** superar 32px de border-radius en ningún elemento — el máximo es 2rem (campo) y 1rem (caja/selector).
- **Don't** emparejar bordes de 1px con sombras difusas de más de 8px en el mismo elemento — es o borde o sombra, no ambos como decoración.
- **Don't** usar iconografía folclórica, paletas tropicales predecibles o ilustraciones "caribeñas" genéricas — la identidad regional se expresa en la palabra y el tono editorial.
