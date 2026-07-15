# Mapa: vox-caribe theme

**Labels**: `wayfinder:map`

---

## Destination

Un homepage con Sage 11 para VoxPopuli Digital que replica la estructura editorial de theatlantic.com, usando los colores de marca de VoxPopuli (Azul Caribe, Naranja Caribe, blanco), **Source Serif 4** para contenido editorial, **Plus Jakarta Sans** para UI, y un diseño de cards sin bordes/sin fondos sobre blanco puro. El tema actual `voxpopuli` se conserva intacto.

## Notes

- **Domain**: WordPress + Sage 11 + Blade + Tailwind v4 + daisyUI 5
- **Skills**: `daisyui` (obligatorio para todo UI), `impeccable` (diseño frontend), `domain-modeling` (terminología), `research` (investigación técnica)
- El tema actual `voxpopuli` en `web/app/themes/voxpopuli/` se conserva sin modificar
- El nuevo theme `vox-caribe` va en `web/app/themes/vox-caribe/`
- Sage 11 es un clon local (no está en composer.lock) — el proceso de scaffolding no es `composer create-project`
- Las decisiones de diseño ya tomadas están abajo; los tickets resuelven lo que aún no está decidido

## Decisions so far

- **Theme scaffolded**: `web/app/themes/vox-caribe/` creado con 22 archivos (ver `.wayfinder/tickets/03-scaffold-vox-caribe.md`)
- **Blade architecture**: front-page.blade.php como template, componentes Blade por sección, View Composer central `FrontPage.php`
- **daisyUI & Vite**: tema `vox-caribe`, Source Serif 4, Vite en puerto 5175, sin border-radius/bordes/depth
- **WordPress queries**: FrontPage view composer creado con queries para hero (sticky), grid (8 posts con thumbnail), archive (mismo mes años anteriores), latest (4 posts), popular (WPP / comment_count fallback), y templates front-page.blade.php orquestador
- **Hero component**: Full-bleed con gradiente, headline abajo-izquierda, kicker + byline, enlace en toda el área
- **Story Grid**: 4 cols × 2 rows, imágenes 4:3, sin byline/fecha, hover headline→accent + imagen→scale-105, separador entre filas
- **Secondary sections**: Archive (3 posts), Podcasts (placeholder), Latest + Popular (2/3 + 1/3), Newsletter (inline signup)
- **Footer**: Azul Caribe, 4 columnas, wordmark mirror rule, copyright
- **Nombre del theme**: `vox-caribe`
- **Inspiración estructural**: Réplica de la estructura de homepage de theatlantic.com, no solo "vibra"
- **Alcance**: Solo homepage (front-page.php). Templates internas quedan fuera de este esfuerzo
- **Colores**: Los existentes de VoxPopuli (Azul Caribe primary, Naranja Caribe accent, blanco base-100, etc.)
- **Tipografía headlines + body**: Source Serif 4 (reemplaza Playfair Display + Literata)
- **Tipografía UI**: Plus Jakarta Sans (se mantiene)
- **Cards**: Sin fondo, sin borde, sin border-radius — contenido flota sobre blanco puro
- **Hero**: Full-bleed con imagen ancho completo y headline superpuesto
- **Story Grid**: 3 columnas desktop, 2 tablet, 1 mobile. Todas las celdas con imagen
- **Cover Story**: No incluye
- **Recommended**: No incluye
- **Archive**: Sí — contenido histórico
- **Podcasts**: Sí, como placeholder (preparado para futuro)
- **Newsletter**: Sí, como placeholder (Brandbook ya contempla estrategia de registro email)
- **Latest**: Sí — timeline cronológico con headline + autor + fecha
- **Popular**: Sí — ranking numerado de lo más leído
- **Magazine / Games / Audio**: Descartados
- **Header**: Se mantiene el drawer actual de VoxPopuli
- **Footer**: Rediseñado con fondo Azul Caribe, wordmark + columnas de links + copyright + redes
- **Orden de secciones**: Header → Hero → Story Grid → Archive → Podcasts → Latest → Popular → Newsletter → Footer

## Not yet specified

- Hero design: altura, overlay, posición headline en mobile (los hallazgos de research muestran que The Atlantic NO tiene hero full-bleed — su homepage empieza directo con story grid. Habrá que decidir si vox-caribe sí tendrá hero o si arranca directo con grid como The Atlantic)
- Sistema de imágenes: qué pipeline de thumbnails/sizes usar. The Atlantic usa srcset con 14 tamaños — VoxPopuli necesita definir su propia estrategia
- Plugin de Popular posts (WP Popular Posts u otro)
- Estrategia de lazy loading y optimización de imágenes
- Integración con el sistema de SEO existente de VoxPopuli

## Hallazgos de investigación disponibles

Los siguientes documentos están en `research/` y contienen información lista para usar al resolver los tickets:

- `research/the-atlantic-responsive-patterns.md` — Análisis completo de responsive design de The Atlantic: breakpoints, grid, navegación, imágenes, tipografía, footer, separadores. Hallazgo clave: **The Atlantic no usa hero full-bleed** en la homepage — arranca directo con el story grid. Usa breakpoint personalizado de 976px (no nativo de Tailwind).
- `research/sage-11-scaffold.md` — Proceso completo para crear un nuevo theme Sage 11. Sage 11 es un clon local, no un paquete Composer. La estrategia es copiar el theme existente y adaptar. Incluye checklist detallado y solución para el conflicto de autoloading PSR-4 con dos themes.

## Out of scope

- Templates internas (single.php, archive.php, page.php, search.php, 404.php) — son un esfuerzo futuro separado
- Modificaciones al theme `voxpopuli` existente
- Migración de datos o configuración de WordPress
- Modo oscuro
- Páginas de categoría, etiqueta, autor
