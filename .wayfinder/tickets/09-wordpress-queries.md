# Task: WordPress queries for homepage sections

**Labels**: `wayfinder:task`

**Blocked by**: `03-scaffold-vox-caribe`

**Blocking**: `05-hero-component`, `06-story-grid-component`, `07-secondary-sections`, `08-footer-component`

---

## Resolution

✅ Queries configuradas para cada sección de la homepage.

### Decisiones tomadas

| Sección | Query | Detalles |
|---|---|---|
| **Hero** | Sticky post (`get_option('sticky_posts')`) | Fallback al post más reciente si no hay sticky |
| **Story Grid** | 8 posts (4 cols × 2 rows) | Todas las categorías, excluye hero, requiere thumbnail (`meta_query` con `_thumbnail_id` EXISTS) |
| **Archive** | 3 posts del mismo mes en años anteriores | `date_query` con month actual + year <= anterior. Si no alcanza, relaja a cualquier año anterior mismo mes |
| **Latest** | 4 posts | Excluye hero + grid + archive. Sin timestamps. |
| **Popular** | WP Popular Posts (5 posts, últimos 30 días) | Fallback a most commented si el plugin no está activo |
| **Podcasts** | `[]` (placeholder) | Preparado para futuro |
| **Newsletter** | Static HTML | Sin query |
| **Footer** | `wp_nav_menu('primary_navigation')` | Usa los menús registrados |

### Archivos creados/modificados
- `app/View/Composers/FrontPage.php` — View Composer con todos los métodos de query
- `app/Providers/ThemeServiceProvider.php` — Registro del composer para `front-page`
- `resources/views/front-page.blade.php` — Template orquestador con componentes Blade

### Pendiente
- Instalar WP Popular Posts en el contenedor: `docker exec -w /app voxpopuli-app-1 composer require wp-plugin/wordpress-popular-posts`
  - El slug exacto puede variar; si no se resuelve, buscar en https://wpackagist.org o instalar manualmente

---

## Question

Configurar las queries de WordPress que alimentan cada sección de la homepage.

Para cada sección, definir:

### Hero (historia destacada)
- ¿Query personalizada (WP_Query) o la natural del loop de homepage?
- ¿Sticky post? ¿último post de una categoría especial "destacado"?
- ¿Criterio: latest post con imagen destacada? ¿último de cierta categoría (Investigación)?

### Story Grid
- ¿Últimas N historias excluyendo la del hero?
- ¿Categorías específicas o todas?
- ¿Cómo asegurar que todas tengan imagen destacada?

### Archive
- ¿Posts de meses/semanas anteriores con un filtro de fecha?
- ¿Categoría específica "Archivo" o tag?

### Latest
- ¿Últimas 10-15 publicaciones (todas, sin filtro)?
- ¿Excluir las ya mostradas en hero/grid?

### Popular
- ¿Plugin (WP Popular Posts, Jetpack Stats) o query con meta_key de contador de visits?
- Si plugin — ¿cuál recomendar e instalar?

### Newsletter
- Sin query (bloque estático), solo HTML placeholder preparado para integración futura

### Footer
- Menús de navegación registrados en WordPress (wp_nav_menu)

Incluir los queries en `app/setup.php` o en un composer/view-composer dedicado.
